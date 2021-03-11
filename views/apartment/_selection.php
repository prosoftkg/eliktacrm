<?
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\slider\Slider;

?>

<div class="ask">
    <?php $form = ActiveForm::begin([
        'action' => ['selection'],
        'method' => 'post',
    ]);
    ?>
    <div class="form-group rel">
        <?
        $roomArr = [1 => '1-комнатные', 2 => '2-х комнатные', 3 => '3-х комнатные', 4 => '4-х комнатные'];?>

        <div class="form-group range-slider-wrap">
            <label class="control-label">Этаж</label>

            <div class="clear"></div>
            <?
            echo Slider::widget([
                'name' => 'floor',
                'value' => '3,5',
                'pluginOptions' => [
                    'min' => 1,
                    'max' => 20,
                    'width' => '500',
                    'ticks' => [1, 2, 3, 4, 5, 6, 7, 8, 9,10,11,12,13,14,15],
                    'ticks_labels' => ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'],
                    'step' => 1,
                    'range' => true,
                ],
            ]);
            ?>

            <?echo $form->field($model, 'room_amount')
                ->dropDownList(
                    $roomArr, // Flat array ('id'=>'label')
                    ['prompt' => 'Кол-во комнат'] // options
                );?>

            <div class="form-group range-slider-wrap">
                <label class="control-label">Площадь</label>

                <div class="clear"></div>
                <?
                echo Slider::widget([
                    'name' => 'area',
                    'value' => '40,60',
                    'pluginOptions' => [
                        'min' => 40,
                        'max' => 120,
                        'width' => '500',
                        'ticks' => [40, 50, 60, 70, 80, 90, 100, 110, 120],
                        'ticks_labels' => ['40м²', '50м²', '60м²', '70м²', '80м²', '90м²', '100м²', '110м²', '120м²'],
                        'step' => 10,
                        'range' => true,
                    ],
                ]);
                ?>

                <div class="form-group range-slider-wrap">
                    <label class="control-label">Цена</label>

                    <div class="clear"></div>
                    <?
                    echo Slider::widget([
                        'name' => 'price',
                        'value' => '30000,60000',
                        'pluginOptions' => [
                            'min' => 30000,
                            'max' => 200000,
                            'width' => '500',
                            'ticks' => [0, 30000, 60000, 90000, 120000, 150000, 180000],
                            'ticks_labels' => ['$0', '$30000', '$60000', '$90000', '$120000', '$150000', '$180000'],
                            'step' => 5000,
                            'range' => true,
                        ],
                    ]);
                    ?>
                    <!--type, input name, input value, options-->
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <? ActiveForm::end(); ?>
        </div>