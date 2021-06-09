<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $model app\models\Building */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->object->title, 'url' => ['/object/' . $model->object->id]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Добавить ход строительства',
    'url' => ['stage/create', 'bid' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать строение',
    'url' => ['building/update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];

$this->params['breadcrumbs'][] = [
    'label' => 'Удалить строение',
    'url' => ['building/delete', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];

?>


<!--<link type="text/css" rel="stylesheet" href="<?/*= Url::base() . '/js/qtip/jquery.qtip.min.css'; */ ?>">
<script type="text/javascript" src="<?/*= Url::base(); */ ?>/js/qtip/jquery.qtip.min.js"></script>-->
<div class="building-view">
    <?php if ($model->img) {
        echo Html::img($model->getMainImg('building', false, false), ['class' => 'object_img']);
    } ?>

    <div class="right-view">

        <h1 class="minor_heading js_minor_heading" company="<?= $model->object->company_id; ?>" object="<?= $model->object->id; ?>"><?= Html::encode($this->title) ?></h1>

        <div class="outer">

            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Срок: </span>
                <span class='object_field'><?= $model->due_quarter . " квартал " . $model->due_year; ?></span>
            </div>

            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Сдан: </span>
                <span class='object_field'><?= $model->is_ready ? 'да' : 'нет' ?></span>
            </div>

            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Адреc: </span>
                <span class='object_field'><?= $model->address; ?></span>
            </div>

            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Количество этажей </span>
                <span class='object_field'><?= $model->stores_amount; ?></span>
            </div>

            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Количество подъездов </span>
                <span class='object_field'><?php echo $model->entryCount ? $model->entryCount : ""; ?></span>
            </div>

            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Количество квартир </span>
                <span class='object_field'><?php echo $model->apartmentCount ? $model->apartmentCount : ""; ?></span>
            </div>
        </div>

        <div class="object-description">
            <?= $model->description; ?>
        </div>

        <?php
        if ($model->stages) {
            echo "<br />";
            echo Html::tag('h4', 'Ходы стриотельства');
            foreach ($model->stages as $stage) {
                echo Html::a(date('d.m.Y', $stage->date_stage), ['/stage/view', 'id' => $stage->id], ['class' => 'btn btn-default']);
            }
        }
        ?>
    </div>

    <?php
    //    echo $this->render('add_entry2', ['building_id' => $model->id, 'building_entry' => $model->entry, 'model' => $model])
    echo $this->render('add_entry2', ['mdlBuilding' => $model])
    ?>

    <div class="clear"></div>

</div>
<script type="text/javascript">
    // Show tooltip on all <a/> elements with title attributes, but only when
    // clicked. Clicking anywhere else on the document will hide the tooltip
    /*window.onload = function () {
     //YOUR JQUERY CODE
     $('a.toolTip[title]').qtip({
     show: 'click',
     hide: 'unfocus'
     });
     }*/

    //    window.onload = function () {
    //        // Apply tooltip on all <a/> elements with title attributes. Mousing over
    //        // these elements will the show tooltip as expected, but mousing onto the
    //        // tooltip is now possible for interaction with it's contents.
    //        var flatNum = 0;
    //        var entryNum = 0;
    //        $('.toolTip').each(function () {
    //            $(this).qtip({
    //                content: $('div.tooltiptext'),
    //                show: 'click',
    //                hide: 'unfocus'
    //            });
    //            flatNum = $(this).attr('flatNum');
    //            entryNum = $(this).attr('entryNum');
    //        });
    //    };
</script>