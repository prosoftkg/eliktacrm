<?php
/**
 * Created by PhpStorm.
 * User: Damir
 * Date: 9/19/16
 * Time: 3:36 PM
 */
use kartik\form\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use app\models\Client;
use app\models\Company;
use yii\helpers\ArrayHelper;

?>
<?php
$form = ActiveForm::begin([
    'id' => 'book-form',
    //'action' => Url::to(['apartment/book']),
    //'validationUrl' => Url::to(['orders/validate']),
    //'enableAjaxValidation' => true,
    'options' => [
        'class' => 'book-form-gq',
        //'data-pjax' => true
    ]
]);

$clients = Client::find()
    ->andFilterWhere([
        'in', 'company_id', ArrayHelper::merge(
            [Yii::$app->user->identity->company_id],
            ArrayHelper::getColumn(Company::find()->andFilterWhere(['owner_id' => Yii::$app->user->id])->select('id')->all(), 'id')
        )
    ])
    ->all();


echo $form->field($client, 'client_name')->widget(Select2::classname(), [
    'data' => ArrayHelper::merge(['Новый клиент'], ArrayHelper::map($clients, 'id', 'fullname')),
    'options' =>
        [
            'placeholder' => 'Выберите клиента',
            'class' => 'client_select_option',
        ],
    'pluginOptions' => [
        'allowClear' => false,
    ],
])->label(false);
?>

    <div class="clear"></div>

    <!--<div class="client_name_value"><?/*= $form->field($client, 'fullname')->textInput(['rows' => 6]) */?></div>-->

<?= $form->field($client, 'phone')->textInput() ?>

<?= $form->field($client, 'phone2')->textInput() ?>

<?= $form->field($deal, 'text')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?php
        /*$layout3 = <<< HTML
            <span class="input-group-addon">From Date</span>
            {input1}
            <span class="input-group-addon">aft</span>
            {separator}
            <span class="input-group-addon">To Date</span
            {input2}
            <span class="input-group-addon kv-date-remove">
                <i class="glyphicon glyphicon-remove"></i>
            </span>
        HTML;*/

        echo '<label class="control-label">Срок бронирования</label>';
        echo DatePicker::widget([
            'layout' => '<span class="input-group-addon">От</span>
                {input1}
                <span class="input-group-addon">до</span>
                {input2}
                ',
            'model' => $deal,
            'attribute' => 'date_from',
            'type' => DatePicker::TYPE_RANGE,
            'attribute2' => 'date_to',
            'pluginOptions' => [
                'autoclose' => true,
                'language' => 'ru',
                'format' => 'yyyy-mm-dd'
            ]
        ]);?>
    </div>

<?= $form->field($deal, 'prepay')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?php
        echo $form->field($deal, 'deal_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Дата сделки'],
            'pluginOptions' => [
                'autoclose' => true,
                'language' => 'ru',
                'format' => 'yyyy-mm-dd'
            ]
        ]);?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Бронировать'), ['class' => 'btn btn-primary book_flats']) ?>
    </div>

<?php
ActiveForm::end();
?>

<?php

$script = <<<SCRIPT

$(".client_select_option").on("change", function(e){
var thisId = $(this);
var myId = $("option:selected", thisId).attr("value");
if(myId==0)
{
    $('.field-client-client_name').after('<div class="form-group client_name_value"><label class="control-label" for="client-phone">ФИО клиента</label><input id="client-fullname" class="form-control" name="Client[fullname]" rows="6" type="text"></div>');
    $('#client-phone').val("");
    $('#client-phone2').val("");
}
else
{
$.ajax({
    url: '/client/apply',
    type: 'post',
    data: {id:myId},
    success: function (data) {
        $('#client-phone').val(data[0]);
        $('#client-phone2').val(data[1]);
        $('.client_name_value').remove();
    }
});
}
});
SCRIPT;
$this->registerJs($script);
?>