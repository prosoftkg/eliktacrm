<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="form">

    <?php $form = ActiveForm::begin(['options' => ['class' => 'price-form-gq']]); ?>

    <?= $form->field($mdlForm, 'usd') ?>
    <?= $form->field($mdlForm, 'kgs') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>