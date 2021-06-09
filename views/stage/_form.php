<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Stage */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {
    $model->date_stage = date('Y-m-d');
    $model->building_id = Yii::$app->request->get('bid');
} else {
    $model->date_stage = date('Y-m-d', $model->date_stage);
}
?>

<div class="stage-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->errorSummary($model) ?>
    <div class="form-group">
        <?php
        echo '<label class="control-label">Дата</label>';
        echo DatePicker::widget([
            'model' => $model,
            'attribute' => 'date_stage',
            'pluginOptions' => [
                'autoclose' => true,
                'language' => 'ru',
                'format' => 'yyyy-mm-dd'
            ]
        ]); ?>
    </div>
    <?php
    $model_name = 'stage';
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

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>
    <?= $form->field($model, 'building_id')->hiddenInput()->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>