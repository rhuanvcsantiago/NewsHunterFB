<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Institute */

$this->title = 'Create Institute';
$this->params['breadcrumbs'][] = ['label' => 'Institutes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="institute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
