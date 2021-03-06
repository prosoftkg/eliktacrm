<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\web\UploadedFile;
use app\models\Objects;
use vova07\imperavi\Widget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Object */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="object-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php /* echo $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
    ]); */ ?>
    <?php
    $model_name = 'object';
    $url = Url::to(['site/img-delete', 'id' => $model->id, 'model_name' => $model_name]);
    $iniImg2 = false;
    $initialPreviewConfig2 = [];
    if (!$model->isNewRecord) {
        if ($model->img && is_dir("images/{$model_name}/" . $model->id)) {
            $imgs = explode(';', $model->img);
            foreach ($imgs as $img) {
                $iniImg2[] = Html::img("@web/images/{$model_name}/" . $model->id . "/s_" . $img, ['class' => 'file-preview-image img-responsive', 'alt' => '']);
                $initialPreviewConfig2[] = ['width' => '80px', 'url' => $url, 'key' => $img, 'model_name' => $model_name, 'model_id' => $model->id];
            }
        }
    }
    echo $form->field($model, 'imageFiles[]')->widget(FileInput::class, [
        'options' => ['accept' => 'image/*', 'multiple' => true],
        'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'overwriteInitial' => false,
            'initialPreview' => $iniImg2,
            'previewFileType' => 'any',
            'initialPreviewConfig' => $initialPreviewConfig2,
        ],
        'pluginEvents' => [
            "filesorted" => "imgSorted",
        ],
    ]);
    ?>

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
    <?= $form->field($model, 'lat')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'lng')->hiddenInput()->label(false) ?>

    <?php
    /* if ($model->isNewRecord)
        echo $form->field($model, 'company_id')->hiddenInput(['value' => $company])->label(false); */ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Добавить') : Yii::t('app', 'Сохранить'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>