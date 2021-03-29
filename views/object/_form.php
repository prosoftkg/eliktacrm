<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\web\UploadedFile;
use app\models\Objects;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="object-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
    ]); ?>

    <?= $form->field($model, 'base_dollar_price')->textInput() ?>

    <?= $form->field($model, 'base_som_price')->textInput() ?>

    <?php
    echo $form->field($model, 'city')
        ->dropDownList(
            Objects::$cities // Flat array ('id'=>'label')
            //['prompt' => ''] // options
        ); ?>
    <label for='form_map' class="form-label">Показать на карте</label>
    <div id="form_map"></div>
    <?php
    echo $form->field($model, 'description')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'clips',
                'fullscreen'
            ]
        ]
    ]);

    ?>
    <?= $form->field($model, 'lat')->textInput() ?>
    <?= $form->field($model, 'lng')->textInput() ?>

    <?php
    /* if ($model->isNewRecord)
        echo $form->field($model, 'company_id')->hiddenInput(['value' => $company])->label(false); */ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>