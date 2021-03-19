<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\Plan;

/* @var $this yii\web\View */
/* @var $model app\models\Plan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            //                   'initialPreviewAsData'=>true,
            //                   'overwriteInitial'=>false,
            //                   'maxFileSize'=>2800
        ]
    ]); ?>

    <?php
    echo $form->field($model, 'comfort_class')
        ->dropDownList(
            Plan::$comfort_class // Flat array ('id'=>'label')
            //['prompt' => ''] // options
        ); ?>
    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'room_count')->textInput(['maxlength' => true]) ?>

    <div class="input_fields_wrap">
        <?php if (!$model->isNewRecord) :
            $rooms = unserialize($model->rooms);
            foreach ($rooms as $key => $val) {
                echo "<div class='room_group'>
                          <input value='$key' type='text' class='form-control form-room' name='room[]'/>
                          <input value='$val' type='text' class='form-control form-area' name='area[]'/>
                          <a href='#' class='remove_field'>Удалить</a>
                      </div>";
            }
        ?>
        <?php endif; ?>
        <div class="appender"></div>
        <button class="add_room">+ Добавить комнаты</button>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<<SCRIPT
    $(document).ready(function() {
        var max_fields      = 20;
        var wrapper         = $(".appender");
        var add_button      = $(".add_room");

        var x = 1;
        $(add_button).click(function(e){
            e.preventDefault();
            if(x < max_fields){
                x++;
                $(wrapper).append('<div class="room_group"><input placeholder="Вид комнаты" type="text" class="form-control form-room" name="room[]"/><input placeholder="Площадь (м²)" type="text" class="form-control form-area" name="area[]"/><a href="#" class="remove_field">Удалить</a></div>');
            }
        });
        $(document).on("click",".remove_field", function(e){ //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
    });
SCRIPT;
$this->registerJs($script);
?>