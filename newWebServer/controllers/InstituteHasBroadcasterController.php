<?php

namespace app\controllers;

use Yii;

use app\models\InstituteHasBroadcasterSearch;
use app\models\InstituteHasBroadcaster;
use app\models\Institute;
use app\models\Broadcaster;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * InstituteHasBroadcasterController implements the CRUD actions for InstituteHasBroadcaster model.
 */
class InstituteHasBroadcasterController extends Controller
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
     * Lists all InstituteHasBroadcaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InstituteHasBroadcasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InstituteHasBroadcaster model.
     * @param integer $id
     * @param integer $Institute_id
     * @param integer $Broadcaster_id
     * @return mixed
     */
    public function actionView($id, $Institute_id, $Broadcaster_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $Institute_id, $Broadcaster_id),
        ]);
    }

    /**
     * Creates a new InstituteHasBroadcaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InstituteHasBroadcaster();
        $institutes = Institute::find()
                        ->select(['id','name'])
                        ->indexBy('id')
                        ->all();;
        $broadcasters = Broadcaster::find()
                        ->select(['id','name'])
                        ->indexBy('id')
                        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'Institute_id' => $model->Institute_id, 'Broadcaster_id' => $model->Broadcaster_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'institutes' => $institutes,
                'broadcasters' => $broadcasters
            ]);
        }
    }

    /**
     * Updates an existing InstituteHasBroadcaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $Institute_id
     * @param integer $Broadcaster_id
     * @return mixed
     */
    public function actionUpdate($id, $Institute_id, $Broadcaster_id)
    {
        $model = $this->findModel($id, $Institute_id, $Broadcaster_id);
        $institutes = Institute::find()
                        ->select(['id','name'])
                        ->indexBy('id')
                        ->all();;
        $broadcasters = Broadcaster::find()
                        ->select(['id','name'])
                        ->indexBy('id')
                        ->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'Institute_id' => $model->Institute_id, 'Broadcaster_id' => $model->Broadcaster_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'institutes' => $institutes,
                'broadcasters' => $broadcasters
            ]);
        }
    }

    /**
     * Deletes an existing InstituteHasBroadcaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $Institute_id
     * @param integer $Broadcaster_id
     * @return mixed
     */
    public function actionDelete($id, $Institute_id, $Broadcaster_id)
    {
        $this->findModel($id, $Institute_id, $Broadcaster_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InstituteHasBroadcaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $Institute_id
     * @param integer $Broadcaster_id
     * @return InstituteHasBroadcaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $Institute_id, $Broadcaster_id)
    {
        if (($model = InstituteHasBroadcaster::findOne(['id' => $id, 'Institute_id' => $Institute_id, 'Broadcaster_id' => $Broadcaster_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
