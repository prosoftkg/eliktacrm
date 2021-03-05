<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use app\models\Building;
use yii\helpers\ArrayHelper;
use app\models\Objects;

/* @var $this yii\web\View */
/* @var $model app\models\Entry */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entry-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'building_id')->hiddenInput(['value' => $building_id])->label(false); ?>

    <?= $form->field($model, 'number')->textInput(); ?>

    <?= $form->field($model, 'apartment_amount')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>