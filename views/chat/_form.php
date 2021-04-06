<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\account\models\Message */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sender_id')->textInput() ?>

    <?= $form->field($model, 'sender_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'receiver_id')->textInput() ?>

    <?= $form->field($model, 'receiver_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sender_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subject_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'archive')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
