<?php

/**
 * Created by PhpStorm.
 * User: Damir
 * Date: 9/13/16
 * Time: 5:41 PM
 */

use yii\helpers\Html;
use yii\widgets\Pjax;

// PROBABLY NOT USED -------------

$apartment_count = $model->apartment_amount;
$height = $model->building->stores_amount;
$result = "";
$allApartments = \yii\helpers\ArrayHelper::index(\app\models\Apartment::find()->where(['entry_id' => $model->id])->orderBy(['id' => SORT_DESC])->all(), 'number');

$prev_apartment_count = $_GET['prev'];

$plans = \app\models\Plan::find()->all(); ?>

<div class="plan-wrapper">
    <?
    echo newerton\fancybox\FancyBox::widget([
        'target' => 'a[rel=fancybox]',
        'helpers' => true,
        'mouse' => true,
        'config' => [
            'maxWidth' => '90%',
            'maxHeight' => '90%',
            'playSpeed' => 7000,
            'padding' => 0,
            'fitToView' => false,
            'width' => '70%',
            'height' => '70%',
            'autoSize' => false,
            'closeClick' => false,
            'openEffect' => 'elastic',
            'closeEffect' => 'elastic',
            'prevEffect' => 'elastic',
            'nextEffect' => 'elastic',
            'closeBtn' => false,
            'openOpacity' => true,
            'helpers' => [
                'title' => ['type' => 'float'],
                'buttons' => [],
                'thumbs' => ['width' => 68, 'height' => 50],
                'overlay' => [
                    'css' => [
                        'background' => 'rgba(0, 0, 0, 0.8)'
                    ]
                ]
            ],
        ]
    ]);

    foreach ($plans as $plan):?>
    <div class="plan-block">
        <?= Html::a(Html::img($plan->getThumbFile()), $plan->getImageFile(), ['rel' => 'fancybox']); ?>
        <div class="clear plan-title"><?= $plan->title; ?></div>
        <div class="clear plan-select" id="<?= $plan->id; ?>">Выбрать для применения</div>
    </div>

    <? endforeach ?>
</div>

<div class="clear"></div>

<?Pjax::begin(['enablePushState' => false, 'id' => 'reload_block']);?>
<div class="wrapper flat-fader">
    <?
    for ($heightStep = $height - 1; $heightStep >= 0; $heightStep--) {
        for ($apartment = 1; $apartment <= $apartment_count; $apartment++) {
            $index = $heightStep * $apartment_count + $apartment + $prev_apartment_count;
            $result .=
                "<div class='flat_plan' id='{$allApartments[$index]->id}'>
               <span>" . ($allApartments[$index]->number) . "</span>
             </div> ";
        }
        $stepY++;

        $result .= "<div class=clear></div>";
    }
    echo $result;?>
</div>

<div class="plan_send btn btn-success">Применить</div>
<?
$script = <<<SCRIPT
    $('document').ready(function() {
        var global_plan = 0;
           $('.plan-select').click(function () {
               var current = $(this).parent();
               global_plan = $(this).attr('id');
               current.removeClass('other').siblings().addClass('other');
               $('.flat-fader').fadeIn();
           });

           var flats = [];
           $('.flat_plan').on('click',function()
           {
                $(this).toggleClass("flat-toggle");
                var dataid = $(this).attr("id");
                var idx = $.inArray(dataid, flats);
                if (idx == -1) {
                  flats.push(dataid);
                } else {
                  flats.splice(idx, 1);
                }
           }
           );

           $('.plan_send').click(function(){
               console.log('views/entry/plan');
               $.ajax({
                   url: '/web/apartment/attach',
                   type: 'post',
                   data:  {plan:global_plan, flats:JSON.stringify(flats)}
               });
           });


    });
SCRIPT;
$this->registerJs($script);
?>