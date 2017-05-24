<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UserFollowInstitute;
use app\models\User;



class SiteController extends Controller
{
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
        $request = Yii::$app->request;
        $connection = Yii::$app->getDb();
        $data = $request->get();

        $msg = "nada";
        
        if( isset($data["email"]) && ( $data["email"] != "" ) ){
            
            try{
                $query = "INSERT INTO user (email) VALUES ('". $data["email"] ."');";
                $numberRowsAffected = $connection->createCommand($query)->execute();        
            }
            catch (Exception $e) {
                $msg = 'Exceção capturada: ' .  $e->getMessage() . "\n";    
            }
            
            $msg = "email [" . $data["email"] . "] cadastrado com sucesso: " . $numberRowsAffected;

        }
        
        return $this->render('index', ["msg" => $msg] );
    }

    public function actionNewuser()
    {
        // Verificar e-mail e institutes ids  
        echo hash_hmac('ripemd160', 'The quick brown fox jumped over the lazy dog.', 'secret');
        // $form->field($model, 'items[]')->checkboxList(['a' => 'Item A', 'b' => 'Item B', 'c' => 'Item C']);
    }

    public function actionEditUserInstitutes()
    {
        $userHash = Yii::$app->request->get("userHash");

        echo "entrei";
        var_dump($userHash);

        // verify if has a UserHash param
        if( $userHash ){
            // verify if is a valid UserHash in database
            $user = User::find()->where(['hash' => $userHash])->one();
            if( $user ){
                $userHasInstitutes = UserFollowInstitute::find()->where(['user_id' => $userHash])->all();
                //return $this->render('editUserInstitutes', ["userHasInstitutes" => $userHasInstitutes] );
                var_dump($userHasInstitutes);
            }
        }
        else 
            $this->render('index', ["msg" => "usuário não cadastrado"] );
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
}
