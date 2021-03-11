<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Request;
use app\models\Reference;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $items = [
        Request::TYPE_BARTER => 'Бартер',
        Request::TYPE_DELAY => 'Задержка',
        Request::TYPE_OTHER => 'Другое'
    ];

    $dataList = ArrayHelper::map(Reference::find()->asArray()->all(), 'id', 'title');
    ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList($items, ['prompt' => 'Выберите тип запроса']); ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <div class="form-group">
        <?php
        echo $form->field($model, 'period')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Дата сделки'],
            'pluginOptions' => [
                'autoclose' => true,
                'language' => 'ru',
                'format' => 'yyyy-mm-dd'
            ]
        ]); ?>
    </div>

    <?= $form->field($model, 'description')->textArea(); ?>

    <?= $form->field($model, 'reference')->dropDownList($dataList, ['prompt' => 'Выберите канал продаж']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
