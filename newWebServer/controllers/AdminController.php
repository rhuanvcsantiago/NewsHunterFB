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
        //$this->redirect("index.php?r=institute");
        return $this->render("cadastros");
    }

    public function actionManageinstitute()
    {
        $this->redirect("index.php?r=institute");
        //return $this->render("cadastros");
    }

    public function actionManagenews()
    {
        $this->redirect("index.php?r=news");
        //return $this->render("cadastros");
    }

    public function actionFetcher()
    {
        return $this->render('fetcher');
    }

    public function actionSendemails()
    {
        return $this->render('sendEmails');
    }

    public function actionUpdatenews()
    {
        //$this->layout = "";
        // atualiza news
        //renderiza pagina de classificar novamente
        // return $this->actionClassifier();
        $request = Yii::$app->request;
        $data = $request->post();

        $query = "START TRANSACTION;\nUSE NewsHunterFFB;\n";

        
        if( isset($data["ignore"]) ){

            foreach ($data["ignore"] as $index => $news_id) {
                $query .= "UPDATE Institute_has_Broadcaster_has_News SET is_relevant = FALSE WHERE news_id=". $news_id . ";\n";
            }
        }

        if( isset($data["approve"]) ){
           foreach ($data["approve"] as $index => $news_id) {     
                $query .= "UPDATE Institute_has_Broadcaster_has_News SET is_relevant = TRUE WHERE news_id=". $news_id . ";\n";
            }
        }

        $query .= "COMMIT;";

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($query);
        $result = $command->queryAll();
        
        //return $this->render( 'updateNews', ['result' => $result] );

        //echo $query;

    }

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionClassifier()
    {
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand("SELECT institute_name, broadcaster_name, news_created_time, news_title, news_content, news_expanded_content, news_shared_link, news_full_picture_link, news_id FROM  NewsHunterFFB.ibn where is_relevant is null order by institute_name, broadcaster_name;");
        $result = $command->queryAll();

        return $this->render( 'classifier', ['result' => $result] );
        
    }

    public function actionConfigs()
    {
        echo "institutos e broadcasters";
        //return $this->render('teste');
    }
}
