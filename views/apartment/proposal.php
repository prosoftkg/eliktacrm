<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<div class="form">

    <?php
    $form = ActiveForm::begin([
        'action' => Url::to(['apartment/createprop']),
        //'validationUrl' => Url::to(['orders/validate']),
        //'enableAjaxValidation' => true,
        'options' => [
            'class' => 'proposal-form-gq',
            //'data-pjax' => true
        ]
    ]);
    ?>
    <?= $form->field($mdlProposal, 'period') ?>
    <?=
    $form->field($mdlProposal, 'base_price')
        ->textInput([
            'disabled' => 'disabled',
            'value' => $mdlApartment->dollar_price ? $mdlApartment->dollar_price : $mdlApartment->getPrice('dollar')
        ]); ?>
    <?= $form->field($mdlProposal, 'prepay'); ?>
    <?= $form->field($mdlProposal, 'apartment')->hiddenInput(['value' => $mdlApartment->id])->label(false); ?>
    <?= $form->field($mdlProposal, 'floor')->hiddenInput(['value' => $floor])->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>