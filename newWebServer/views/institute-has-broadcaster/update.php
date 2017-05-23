<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\models\InstituteHasBroadcaster */

$this->title = 'Atualizar broadcasters para institutos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Institutos e Broadcasters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'Institute_id' => $model->Institute_id, 'Broadcaster_id' => $model->Broadcaster_id]];
$this->params['breadcrumbs'][] = 'Update';

$instituteMap = ArrayHelper::map($institutes, 'id', 'name');
$broadcasterMap = ArrayHelper::map($broadcasters, 'id', 'name');

?>
<div class="institute-has-broadcaster-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'instituteMap' => $instituteMap,
        'broadcasterMap' => $broadcasterMap,
    ]) ?>

</div>
