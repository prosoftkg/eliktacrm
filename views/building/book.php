<?php
/**
 * Created by PhpStorm.
 * User: Damir
 * Date: 9/19/16
 * Time: 3:36 PM
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

?>
<?php
$form = ActiveForm::begin([
    'id' => 'book-form',
    //'action' => Url::to(['apartment/book']),
    //'validationUrl' => Url::to(['orders/validate']),
    'enableAjaxValidation' => true,
    'options' => [
        'class' => 'book-form-gq',
        //'data-pjax' => true
    ]
]); ?>

<?= $form->field($client, 'fullname')->textInput(['rows' => 6]) ?>

<?= $form->field($client, 'phone')->textInput() ?>

<?= $form->field($client, 'phone2')->textInput() ?>

<?= $form->field($book, 'text')->textarea(['rows' => 6]) ?>

<?= $form->field($book, 'date_from')->textInput() ?>

<?= $form->field($book, 'date_to')->textInput() ?>

<?= $form->field($client, 'prepay')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Бронировать'), ['class' => 'btn btn-primary book_flats']) ?>
    </div>

<?php
ActiveForm::end();
?>