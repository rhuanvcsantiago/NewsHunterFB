<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\InstituteHasBroadcaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="institute-has-broadcaster-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'Institute_id')->dropdownList($instituteMap) ?>

    <?= $form->field($model, 'Broadcaster_id')->dropdownList($broadcasterMap) ?>

    <?= $form->field($model, 'userName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
