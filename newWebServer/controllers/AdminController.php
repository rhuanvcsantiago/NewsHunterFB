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
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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

        return $this->render( 'index', ['result' => $result] );
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
        $command = $connection->createCommand("SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id FROM  NewsHunterFFB.ibn where is_relevant is null order by institute_name, broadcaster_name;");
        $newsNotSended = $command->queryAll();

        // [done] pegar lista de emails.        
        $command = $connection->createCommand("SELECT email FROM  User");
        $mailsToSend = $command->queryAll();

        $updateResult = [];
        $updateResult["result"] = "";
        $updateResult["enviar"] = [];

        // se chegar aqui atraves de post e tiver o dado sim => enviar emails.
        if ( $request->getIsPost() && ( $request->post()["enviar"] == "sim") ){
            $updateResult["enviar"] = "entrou";

            if( ( count($newsNotSended) > 0 ) && ( count($newsNotSended) > 0 ) )
            // montar email design.
            Yii::$app->mailer->compose()
    ->setFrom('from@domain.com')
    ->setTo('to@domain.com')
    ->setSubject('Message subject')
    ->setTextBody('Plain text content')
    ->setHtmlBody('<b>HTML content</b>')
    ->send();
    /*        
            Yii::$app->mailer->compose( 'emailnews',["lastNews" => $newsNotSended] ) // a view rendering result becomes the message body here
                ->setFrom('from@domain.com')
                ->setTo('to@domain.com')
                ->setSubject('Message subject')
                ->send();
*/
            $updateResult["result"] = "success";

            // Testing and debugging
            // yii\mail\BaseMailer::useFileTransport
            
            // enviar emails;
            // transaction update das noticias enviadas.
                // mostrar tela novamente com erros ou sem erros.
                // $updateResult["result"] = "success";
                // $updateResult["result"] = "error";
        }

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

    public function actionConfigs()
    {
        echo "institutos e broadcasters";
        //return $this->render('teste');
    }
}
