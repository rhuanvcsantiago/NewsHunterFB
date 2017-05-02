<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Broadcaster */

$this->title = 'Update Broadcaster: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Broadcasters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="broadcaster-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
