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
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Apartment;
use app\models\Sold;
use app\models\Client;
use app\models\Company;
use app\models\Reference;
use yii\helpers\ArrayHelper;

?>
<?php
$form = ActiveForm::begin([
    'id' => 'sold-form',
    //'action' => Url::to(['apartment/sold']),
    //'validationUrl' => Url::to(['orders/validate']),
    //'enableAjaxValidation' => true,
    'options' => [
        'class' => 'sold-form-gq',
        //'data-pjax' => true
    ]
]);
$apartment = Apartment::findOne($apartment_id);
if ($apartment->dollar_price == 0) {
    $apartment_price = $apartment->getPrice('dollar');
} else {
    $apartment_price = $apartment->dollar_price;
}

$left_sum = $apartment_price - $deal->prepay;

if (!$apartment->client) {
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
} else {
    echo $form->field($client, 'fullname')->textInput();
    echo $form->field($client, 'client_name')->hiddenInput()->label(false);

}
?>
<?= $form->field($client, 'phone')->textInput() ?>
<?= $form->field($client, 'phone2')->textInput() ?>
<?= $form->field($deal, 'text')->textarea(['rows' => 6]) ?>

<div class="form-group form-book-date">
    <?php
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

<div class="form-group">
    <?= $form->field($client, 'birthday')->textInput(["onfocus" => "(this.type='date')"]); ?>
</div>
<?= $form->field($client, 'passport_num')->textInput() ?>
<?= $form->field($client, 'email')->textInput() ?>
<?= $form->field($client, 'address')->textInput() ?>
<?= $form->field($deal, 'prepay')->textInput() ?>
<?= $form->field($deal, 'discount')->textInput() ?>
<?= $form->field($deal, 'left_sum')->textInput(['value' => $left_sum, 'primary' => $left_sum, 'base' => $apartment_price]);
$reference = Reference::find()->asArray()->all();
echo $form->field($deal, 'reference')->widget(Select2::classname(), [
       'data' => ArrayHelper::map($reference, 'id', 'title'),
       'options' =>
           [
               'placeholder' => 'Выберите канал продаж',
               'class' => 'client_select_option',
           ],
       'pluginOptions' => [
           'allowClear' => false,
       ],
   ]);
?>
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
<? /*=$form->field($model, 'reference')
 ?>      ->dropDownList(
           [Sold::REFERENCE_INTERNET,Sold::REFERENCE_RADIO,Sold::REFERENCE_RELATIVES,Sold::REFERENCE_TV,Sold::REFERENCE_OTHER],           // Flat array ('id'=>'label')
           ['prompt'=>'Выберите из списка']    // options
       );*/
?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'Сохранить как проданное'), ['class' => 'btn btn-primary book_flats']) ?>
</div>

<?php
ActiveForm::end();
$script = <<<SCRIPT

$(".client_select_option").on("change", function(e){
var thisId = $(this);
var myId = $("option:selected", thisId).attr("value");
if(myId==0)
{
    $('.field-client-client_name').after('<div class="form-group client_name_value"><label class="control-label" for="client-phone">ФИО клиента</label><input id="client-fullname" class="form-control" name="Client[fullname]" rows="6" type="text"></div>');
    $('#client-phone').val("");
    $('#client-phone2').val("");
    $('.form-book-date').css('display','none');
}
else
{
$('.form-book-date').css('display','block');
$.ajax({
    url: '/client/apply',
    type: 'post',
    data: {id:myId},
    success: function (data) {
        $('#client-phone').val(data[0]);
        $('#client-phone2').val(data[1]);
        $('#client-passport_num').val(data[2]);
        $('#client-email').val(data[3]);
        $('#client-address').val(data[4]);
        $('#client-birthday').val(data[5]);
        $('.client_name_value').remove();
    }
});
}
});
SCRIPT;
$this->registerJs($script);
?>



