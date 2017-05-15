<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;


/* @var $this yii\web\View */
/* @var $model app\models\InstituteHasBroadcaster */

$this->title = 'Create Institute Has Broadcaster';
$this->params['breadcrumbs'][] = ['label' => 'Institute Has Broadcasters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$instituteMap = ArrayHelper::map($institutes, 'id', 'name');
$broadcasterMap = ArrayHelper::map($broadcasters, 'id', 'name');

//VarDumper::dump($instituteMap);

?>
<div class="institute-has-broadcaster-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'instituteMap' => $instituteMap,
        'broadcasterMap' => $broadcasterMap,
        
    ]) ?>

</div>


