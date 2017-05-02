<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Broadcaster */

$this->title = 'Create Broadcaster';
$this->params['breadcrumbs'][] = ['label' => 'Broadcasters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broadcaster-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
