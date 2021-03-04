<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\User;
use kartik\date\DatePicker;
use app\models\Assignment;

/* @var $this yii\web\View */
/* @var $model app\models\Assignment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="assignment-form" style="width: 400px">
    <?
    $typeArr = [
        Assignment::ASSIGNMENT_TYPE_CALL => 'Звонок клиенту',
        Assignment::ASSIGNMENT_TYPE_MEETING => 'Встреча с клиентом',
        Assignment::ASSIGNMENT_TYPE_DEMONSTRATION => 'Демонстрация',
        Assignment::ASSIGNMENT_TYPE_LETTER => 'Письмо клиенту',
        Assignment::ASSIGNMENT_TYPE_TASK => 'Задание',
    ];
    $statusArr = [Assignment::ASSIGNMENT_STATUS_OPENED => 'Открыт', Assignment::ASSIGNMENT_STATUS_CLOSED => 'Сделано'];
    $priorityArr = [Assignment::ASSIGNMENT_PRIORITY_URGENT => 'Срочно', Assignment::ASSIGNMENT_PRIORITY_STANDARD => 'Стандартный'];
    $users = ArrayHelper::map(User::find()
        ->where(['parent_id' => Yii::$app->user->id])
        ->all(), 'id', 'profile.name');
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?
    echo $form->field($model, 'type')
        ->dropDownList(
            $typeArr,
            ['prompt' => 'Выберите тип задачи'] // options
        );
    ?>

    <?
    echo $form->field($model, 'user_id')->widget(Select2::classname(), [
        'data' => $users,
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите менеджера.'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    ?>

    <?
    echo $form->field($model, 'priority')
        ->dropDownList(
            $priorityArr, // Flat array ('id'=>'label')
            ['prompt' => 'Выберите приоритет задачи'] // options
        );
    ?>

    <?
    echo $form->field($model, 'status')
        ->dropDownList(
            $statusArr, // Flat array ('id'=>'label')
            ['prompt' => 'Выберите статус задачи'] // options
        );
    ?>

    <div class="form-group">
        <?php
        echo '<label class="control-label">Срок задач</label>';
        echo DatePicker::widget([
            'layout' => '<span class="input-group-addon">От</span>
                    {input1}
                    <span class="input-group-addon">до</span>
                    {input2}
                    ',
            'model' => $model,
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

    <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
