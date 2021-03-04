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
    <?
   /* if ($model->img) {
        $img = [Url::base() . '/images/building/' . $model->img];
    } else
        $img = false;*/
    echo $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            //'initialPreview' => $img,
            'initialPreviewAsData' => true,
            'overwriteInitial' => false,
            'maxFileSize' => 2800
        ]
    ]); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stores_amount')->textInput() ?>

    <?
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
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
