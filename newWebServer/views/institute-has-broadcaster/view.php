<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InstituteHasBroadcaster */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Institute Has Broadcasters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="institute-has-broadcaster-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'Institute_id' => $model->Institute_id, 'Broadcaster_id' => $model->Broadcaster_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'Institute_id' => $model->Institute_id, 'Broadcaster_id' => $model->Broadcaster_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'Institute_id',
            'Broadcaster_id',
            'userName',
            'link',
        ],
    ]) ?>

</div>
