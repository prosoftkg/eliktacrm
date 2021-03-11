<?php

use app\models\Entry;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$entry = new Entry();
$form = ActiveForm::begin([
    'id' => 'orders-form',
    'action' => Url::to(['entry/create']),
    //'validationUrl' => Url::to(['orders/validate']),
    //'enableClientValidation' => true,
    //'enableAjaxValidation' => true,
    'options' => [
        'class' => 'orders-form-gq',
        'data-pjax' => true
    ]
]); ?>

<?= $form->field($entry, 'building_id')->hiddenInput(['value' => $mdlBuilding->id])->label(false); ?>
<?= Html::hiddenInput('stores', $mdlBuilding->stores_amount); ?>
<?= $form->field($entry, 'number')->textInput(); ?>
<?= $form->field($entry, 'apartment_amount')->textInput() ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-primary append_entry']) ?>
</div>

<?php
ActiveForm::end(); ?>