<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\models\Objects;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Building */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="building-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?php
    $model_name = 'building';
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

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stores_amount')->textInput() ?>

    <?= $form->field($model, 'due_quarter')->textInput() ?>

    <?= $form->field($model, 'due_year')->textInput() ?>
    <?= $form->field($model, 'is_ready')->checkbox() ?>

    <?php
    if ($model->isNewRecord)
        echo $form->field($model, 'object_id')->hiddenInput(['value' => $object])->label(false);
    /*else
        echo $form->field($model, 'object_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Object::find()->where(['company_id'=>Yii::$app->user->identity->company_id])->all(), 'id', 'title'),
            'options' => ['placeholder' => 'Выберите объект ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);*/

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>