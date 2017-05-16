<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\News;
use yii\data\Pagination;
use yii\helpers\VarDumper;


class AdminController extends Controller
{
    
    public $layout = "admin";
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['*'],
                'rules' => [
                    [
                        //'actions' => ['index', 'cadastros', 'manageinstitute', 'managenews', 'fetcher', 'sendemails', 'classifier', 'logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post', 'get'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id FROM  NewsHunterFFB.ibn where is_relevant is null order by institute_name, broadcaster_name;");
        $result = $command->queryAll();

        $queryMaxIds = "SELECT max(id) as id, `type` FROM Execution group by type";
        $command = $connection->createCommand( $queryMaxIds );
        $MaxIds = $command->queryAll();

        // sempre tem  que ter informacao do fetcher e do envio de emails
        // preenchimento default do banco com valor zero
        $queryLastExecutions  = "select `type`, `timestamp` as lastExecutionTime FROM Execution where id = ".$MaxIds[0]["id"]." or id = ".$MaxIds[1]["id"]." ";
        $command = $connection->createCommand( $queryLastExecutions );
        $lastExecutions = $command->queryAll();
            
        return $this->render( 'index', ['result' => $result, 'lastExecutions' => $lastExecutions] );
    }

    public function actionCadastros()
    {
        return $this->render("cadastros");
    }

    public function actionManageinstitute()
    {
        $this->redirect("index.php?r=institute");
    }

    public function actionManagenews()
    {
        $this->redirect("index.php?r=news");
    }

    public function actionFetcher()
    {
        return $this->render('fetcher');
    }

    public function actionSendemails()
    {
        $request = Yii::$app->request;
        $connection = Yii::$app->getDb();

        // [done] pegar lista de noticias a serem enviadas.
        $queryNewsNotSended = "SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id, mail_sent FROM  NewsHunterFFB.ibn where is_relevant is true and mail_sent is null order by institute_name, broadcaster_name;";
        $command = $connection->createCommand( $queryNewsNotSended );
        $newsNotSended = $command->queryAll();

        // [done] pegar lista de emails.
        $queryMailList = "SELECT email FROM User";        
        $command = $connection->createCommand( $queryMailList );
        $mailsToSend = $command->queryAll();

        $updateResult = [];
        $updateResult["result"] = "";
        $updateResult["enviar"] = [];

        // se chegar aqui atraves de post e tiver o dado sim => enviar emails.
        if ( $request->getIsPost() && ( $request->post()["enviar"] == "sim") ){

            /* save execution time */
            date_default_timezone_set('America/Fortaleza');
            $time = date("Y-m-d H:i:s");
            $queryInsertExecution = 'INSERT INTO Execution (type, timestamp) values("mail", "' . $time . '")';
            $command = $connection->createCommand( $queryInsertExecution );
            $command->execute();  
            /* save execution time */          

            $updateResult["result"] = "error";

            if( (count($newsNotSended) > 0) && (count($mailsToSend) > 0) ){
                
                //PEGAR DADOS USUARIO -> ASSINOU -> ENTIDADE

                // !!! REQUISICAO DEMORA MUITO
                // ACHAR UM JEITO TIPO WEBSOCKETS OU AJAX DE ACOMPANHAR ENVIO DE EMAIL POR EMAIL
                foreach ($mailsToSend as $position => $user) {
                    Yii::$app->mailer->compose( 'emailnews2',["lastNews" => $newsNotSended] ) // a view rendering result becomes the message body here
                        ->setFrom('phodaoce@gmail.com')
                        ->setTo( $user["email"] )
                        ->setSubject('Novidades News Hunter FFB')
                        ->send();
                }

                try {
                    $transaction = $connection->beginTransaction();

                    foreach ($newsNotSended as $position => $news) {
                        $query = "UPDATE Institute_has_Broadcaster_has_News SET mail_sent = TRUE WHERE news_id=". $news["news_id"] . ";\n";
                        $connection->createCommand( $query )->execute();
                    }

                    $transaction->commit();
                    $updateResult["result"] = "success";

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    $updateResult["result"] = "error";
                    //throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    $updateResult["result"] = "error";
                    //throw $e;
                }
            }       
        }

        $command = $connection->createCommand( $queryNewsNotSended );
        $newsNotSended = $command->queryAll();

        return $this->render('sendEmails', ['result' => $newsNotSended, 'updateResult' => $updateResult]);
    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionClassifier()
    {
        $request = Yii::$app->request;
        $connection = Yii::$app->getDb();
        
        $updateResult = [];
        $updateResult["result"]  = [];
        $updateResult["ignore"]  = [];
        $updateResult["approve"] = [];

        if ( $request->getIsPost() ){

            $data = $request->post();
            $transaction = $connection->beginTransaction();

            try {
                if( isset($data["ignore"]) && ( count($data["ignore"]) > 0 )  ){
                    
                    $updateResult["result"] = "success";
                    $updateResult["ignore"] = $data["ignore"];

                    foreach (  json_decode( $data["ignore"] ) as $index => $news_id) {
                        $query = "UPDATE Institute_has_Broadcaster_has_News SET is_relevant = FALSE WHERE news_id=". $news_id . ";\n";
                        $connection->createCommand($query)->execute();
                    }
                }

                if( isset($data["approve"]) && ( count($data["approve"]) > 0 ) ){
                    
                    $updateResult["result"] = "success";
                    $updateResult["approve"] = $data["approve"];

                    foreach ( json_decode( $data["approve"] ) as $index => $news_id) {     
                        $query = "UPDATE Institute_has_Broadcaster_has_News SET is_relevant = TRUE WHERE news_id=". $news_id . ";\n";
                        $connection->createCommand($query)->execute();
                    }
                }
                
                $transaction->commit(); 

            } catch (\Exception $e) {
                $transaction->rollBack();
                $updateResult["result"] = "error";
                //throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                $updateResult["result"] = "error";
                //throw $e;
            }

        }

        $command = $connection->createCommand("SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id FROM  NewsHunterFFB.ibn where is_relevant is null order by institute_name, broadcaster_name;");
        $result = $command->queryAll();

        return $this->render( 'classifier', ['result' => $result, 'updateResult' => $updateResult] );
        
    }

}
