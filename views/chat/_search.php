<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\account\models\MessageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="message-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'sender_id') ?>

    <?= $form->field($model, 'sender_name') ?>

    <?= $form->field($model, 'receiver_id') ?>

    <?= $form->field($model, 'receiver_name') ?>

    <?php // echo $form->field($model, 'sender_email') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'subject_link') ?>

    <?php // echo $form->field($model, 'archive') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
