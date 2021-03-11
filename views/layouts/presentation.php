<?php
use app\models\Presentation;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Modal;
?>

    <div class="callback" id="call_back">
        <?php
        $model = Yii::createObject([
            'class' => Presentation::className()
        ]);
        $form = ActiveForm::begin([
            'id' => 'presentation-form',
            'action' => Url::to(['presentation/send']),
            'options' => [
                'class' => 'presentation-form-gq',
            ]
        ]); ?>

        <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'class' => 'input_presentation', 'placeholder' => 'Ваше имя'])->label(false); ?>
        <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'input_presentation', 'placeholder' => 'Ваш телефон'])->label(false); ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'input_presentation', 'placeholder' => 'Электронная почта'])->label(false); ?>

        <div class="form-group">
            <?= Html::submitButton('Заказать', ['class' => 'presentation_click tariff-order']); ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php

$script = <<<SCRIPT
$('.presentation_click').click(function(e){
    e.stopImmediatePropagation();
    e.preventDefault();
    var fullname = $('#presentation-fullname').val();
    var phone = $('#presentation-phone').val();
    var email = $('#presentation-email').val();

    var form = $(".presentation-form-gq");
    // return false if form still have some validation errors
    if (form.find('.has-error').length) {
                return false;
    }
    // submit form
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data:  {fullname:fullname, phone:phone,email:email },
        success: function (response) {
            $('.modal-body').html(response);
        }
    });
    return false;
});
SCRIPT;
$this->registerJs($script);
?>