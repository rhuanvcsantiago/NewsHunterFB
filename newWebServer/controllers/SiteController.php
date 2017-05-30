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
use app\models\Institute;
use yii\validators\EmailValidator;


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
        
        $msg = [];

        $institutes = Institute::find()->all();

        $userEmail = json_decode( Yii::$app->request->post("email") );
        $institutesIdList = json_decode( Yii::$app->request->post("institutesIdList") );
        
        if ( $userEmail && $institutesIdList ) {

            //verifica se eh email válido
            $validator = new EmailValidator();
            if ( !$validator->validate($userEmail, $error)) {
                $msg["type"] = "danger";
                $msg["value"] = "Email [" . $userEmail . "] inválido!";
            } 
            else {
                //verifica se email já existe
                $user = User::find()->where(['email' => $userEmail])->one();   
                if( $user ){
                    $msg["type"] = "danger";
                    $msg["value"] = "Email [" . $userEmail . "] já cadastrado!"; 
                } else {
                    //cadastra novo usuario
                    $userHash = hash_hmac('ripemd160', $userEmail, '820019');
                    $user = new User();
                    $user->email = $userEmail;
                    $user->hash = $userHash;
                    $user->save();

                    //cadastra institutos seguidos pelo usuario
                    foreach ($institutesIdList as $pos => $instituteId) {
                        $userFollowInstitute = new UserFollowInstitute();
                        $userFollowInstitute->User_id = $user->id; 
                        $userFollowInstitute->Institute_id = $instituteId;
                        $userFollowInstitute->save();
                    }

                    $msg["type"] = "success";
                    $msg["value"] = "Email [" . $userEmail . "] cadastrado com sucesso!";
                } // fim else -> cadastra novo usuario
            } // fim else -> email valido 
        } // fim if -> chegou através do post

        return $this->render('index', ["msg" => $msg, "institutes" => $institutes] );
    }

    public function actionEditUserFollowingInstitutes()
    {
        $userHash  = Yii::$app->request->get("userHash");
        $userEmail = Yii::$app->request->get("userEmail");
        $institutesIdList = Yii::$app->request->get("institutesIdList");
        
        //verify if has params
        if( !$userHash || !$userEmail )
            return $this->render('editUserFollowingInstitutes', [ "msgm" => ["type" => "danger", "value" => "Dados incompletos. Email e hash são requeridos."] ] );

        //verify email 
        $validator = new EmailValidator();
        if ( !$validator->validate($userEmail, $error) )
            return $this->render('editUserFollowingInstitutes', [ "msgm" => ["type" => "danger", "value" => "Email Inválido."] ] );

        //verify if is a valid user    
        $user = User::find()->where(['email' => $userEmail])->one();  
        if( !$user )
            return $this->render('editUserFollowingInstitutes', [ "msgm" => ["type" => "danger", "value" => "Usuário não existente."] ] );
                 
        // verify UserHash == user Hash
        if( $user->hash != $userHash )
            return $this->render('editUserFollowingInstitutes', [ "msgm" => ["type" => "danger", "value" => "Hash inválido para esse usuário."] ] );

        $userHasInstitutes = UserFollowInstitute::find()->where(['user_id' => $user->id])->all();    
        // se tiver lista, atualiza
        if( $institutesIdList ){
            $institutesIdList = explode(",", Yii::$app->request->get("institutesIdList") );
            //var_dump($institutesIdList);
            //exit();
            // delete rows
            foreach ($userHasInstitutes as $pos => $userHasInstitute) {
                $isNotInTheList = true;
                
                foreach ($institutesIdList as $pos2 => $instituteId) {
                    if( $userHasInstitute->Institute_id == $instituteId )
                        $isNotInTheList = false;    
                }

                if( $isNotInTheList )
                    $userHasInstitute->delete();
            }

            // crate new values
            foreach ($institutesIdList as $pos => $instituteId) {
                $isNotInTheDataBase = true;
                
                foreach ($userHasInstitutes as $pos2 => $userHasInstitute) {
                    if( $instituteId == $userHasInstitute->Institute_id )
                        $isNotInTheDataBase = false;
                }

                if( $isNotInTheDataBase ) {
                    $userFollowInstitute = new UserFollowInstitute();
                    $userFollowInstitute->User_id = $user->id; 
                    $userFollowInstitute->Institute_id = $instituteId;
                    $userFollowInstitute->save();
                }
            }

        }

        // mostra tela
        $userHasInstitutes = UserFollowInstitute::find()->joinWith('institute')->joinWith('user')->where(['user_id' => $user->id])->all();
        $Institutes = Institute::find()->all();

        return $this->render('editUserFollowingInstitutes', ["userHasInstitutes" => $userHasInstitutes, "institutes" => $Institutes] );
        
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
