<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Plan;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ApartmentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="apartment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]);

    $roomArr = [1=>'1-комнатные', 2=>'2-х комнатные',3=>'3-х комнатные'];

    echo $form->field($model, 'room_amount')->textInput();

//    echo $form->field($model, 'room_amount')
//        ->dropDownList(
//            $roomArr, // Flat array ('id'=>'label')
//            ['prompt' => ''] // options
//        ); ?>

    <?= $form->field($model, 'entry_num') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'building_id') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
