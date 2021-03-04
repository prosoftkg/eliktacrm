<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use app\models\Payment;
use app\models\Deal;

/* @var $this yii\web\View */
/* @var $model app\models\Client */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Удалить клиента',
    'url' => ['client/delete', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать клиента',
    'url' => ['client/update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="client-view">
    <h1 class="minor_heading_clean">Персональная информация клиента <?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'phone',
            'phone2',
            'birthday',
            'passport_num',
            'email:email',
            'address',
            'prepay',
        ],
    ]);
    ?>

    <h1 class="minor_heading_clean">История платежей</h1>
    <div id="reload-content1">
        <?php
        Pjax::begin(['enablePushState' => true, 'id' => 'reload-content']);?>
        <?php
        //$statusArr = [Payment::STATUS_NOT_PAID => 'Не оплачено',Payment::STATUS_PAID=>'Оплачено'];
        echo GridView::widget(['dataProvider' => $dataProvider,
            'summary' => false,
            'columns' => [
                [
                    'attribute' => 'Дата',
                    'value' => 'pay_date',
                ],
                [
                    'attribute' => 'Сумма',
                    'value' => 'sum',
                ],
                [
                    'label'=>'Сделка',
                    'attribute'=>'apartment_id',
                    'value'=>'deal.deal'
                ],
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{status}{remove}',
                    'buttons' => [
                        'status' => function ($url, $payment) {
                                return Html::activeDropDownList($payment, 'status', [Payment::STATUS_NOT_PAID => 'Не оплачено', Payment::STATUS_PAID => 'Оплачено'], ['class' => 'status-list', 'payment-id' => $payment->id]);
                            },
                        'remove' => function ($url, $payment) {
                                return "<span title='" . Yii::t('app', 'Удалить') . "' class='glyphicon glyphicon-remove payment-remove' payment-id=" . $payment->id . "></span>";
                            }
                    ],
                ],
            ]
        ]);

        /*if ($payItems) {
            foreach ($payItems as $payItem):?>
                <div class="payment-row">
                    <div class="payment-date">
                        <span class="payment-label">Дата:</span>
                        <?= $payItem->pay_date; ?>
                    </div>
                    <div class="payment-sum">
                        <span class="payment-label">Сумма:</span>
                        <?= $payItem->sum; ?>
                    </div>
                </div>
            <?
            endforeach;
        }*/
        Pjax::end();
        ?>
    </div>
    <h1 class="minor_heading_clean">Добавить платеж</h1>

    <div class="input_fields_wrap">
        <?php
        $form = ActiveForm::begin([
            'options' => [
                'class' => 'payment-form',
                //'data-pjax' => true
            ]]);
        $payment = new Payment();
        echo $form->field($payment, 'sum')->textInput(['maxlength' => true]);
        echo $form->field($payment, 'client_id')->hiddenInput(['value' => $model->id])->label(false);
        ?>

        <div class="form-group">
            <?
            echo $form->field($payment, 'pay_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Введите дату платежа ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'language' => 'ru',
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            ?>
        </div>

        <div class="form-group">
            <?
            $deals = ArrayHelper::map(Deal::find()->where(['client_id'=>$model->id])->all(),'apartment_id','deal');
            echo $form->field($payment, 'apartment_id')
                ->dropDownList(
                    $deals, // Flat array ('id'=>'label')
                    ['prompt' => 'Выберите сделку'] // options
                );
            ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton($payment->isNewRecord ? 'Добавить' : 'Редактировать', ['class' => $payment->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>

    <?php
    $script = <<<SCRIPT
    $("body").on("click",".btn", function(e){
        e.preventDefault();
        var form = $('.payment_form');
        var clientId = $('#payment-client_id').val();
        var apartmentId = $('#payment-apartment_id').val();
        var sum = $('#payment-sum').val();
        var date = $('#payment-pay_date').val();
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
            return false;
        }
        // submit form
        $.ajax({
            url: '/client/payment',
            type: 'post',
            data:{clientId:clientId,apartmentId:apartmentId,sum:sum,date:date},
            success: function(data){
                if(data==true)
                {
                    $.pjax.reload({container: '#reload-content'});
                }
            }
        });
        return false;
    });

    $("body").on("change",".status-list", function(e){
        var status = $(this).val();
        var paymentId = $(this).attr('payment-id');
         $.ajax({
            url: '/client/status',
            type: 'post',
            data:{status:status,paymentId:paymentId},
            success: function(){
                  alert("Сохранено");
            }
        });
    });

    $("body").on("click",".payment-remove", function(){
            var paymentId = $(this).attr('payment-id');
             $.ajax({
                url: '/client/remove',
                type: 'post',
                data:{paymentId:paymentId},
                success: function(data){
                    if(data==true)
                    {
                        $.pjax.reload({container: '#reload-content'});
                    }
                }
            });
        });
SCRIPT;
$this->registerJs($script);
?>


