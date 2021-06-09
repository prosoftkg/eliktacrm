<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Object */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Объекты компании'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Добавить строение',
    'url' => ['building/create', 'object' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Удалить',
    'url' => ['object/delete', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать',
    'url' => ['object/update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="object-view">
    <?php
    if ($model->img) {
        echo Html::img($model->getMainImg('object', false, false), ['class' => 'object_img']);
    }
    ?>

    <div class="right-view">

        <h1 class="minor_heading"><?= Html::encode($this->title) ?></h1>

        <div class="outer">
            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Цена: </span>
                <span class='object_field'>$<?= $model->base_dollar_price . " ({$model->base_som_price} сом) за м²"; ?></span>
            </div>
        </div>

        <div class="object-description">
            <?= $model->description; ?>
        </div>
    </div>

    <div class="clear"></div>

    <div class="object-building">
        <h1 class="medium_heading">Дома жилого коплекса</h1>
        <?php
        foreach ($model->building as $building) : ?>
            <div class="building-block">
                <?= Html::a($building->title, ['/building/view', 'id' => $building->id], ['class' => 'building-title']); ?>
                <?php
                if ($building->img) {
                    $img = Html::img($building->getMainImg('building', true, false), ['class' => 'portrait1']);
                    echo Html::a($img, ['/building/view', 'id' => $building->id]);
                }
                ?>
                <div class="wrap_nums">
                    <div class="lower_part">
                        <?php
                        if ($building->entry) {
                            switch ($building->entryCount) {
                                case $building->entryCount > 1:
                                    $suffix = "подъезда";
                                    break;
                                case $building->entryCount > 4:
                                    $suffix = "подъездов";
                                    break;
                                default:
                                    $suffix = "подъезд";
                            }
                            echo "<span class='entry_count'>{$building->entryCount} {$suffix} </span>";
                        }

                        if ($building->apartmentCount) {
                            echo "<span class='apartment_count'>{$building->apartmentCount} квартир </span>";
                        }
                        ?>
                    </div>
                </div>
                <div class="area_price">
                    <div class="area_row">
                        <ol class="ol_circle">
                            <li><span class="left_ol">120</span><span class="right_ol">$29 000 - $35 000</span></li>

                            <li><span class="left_ol">50</span><span class="right_ol">$45 000 - $75 000</span></li>

                            <li><span class="left_ol">30</span><span class="right_ol">$30 000 - $70 000</span></li>
                        </ol>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    if ($model->lat && $model->lng) {
        echo Html::tag('div', '', ['id' => 'view_map', 'data-lat' => $model->lat, 'data-lng' => $model->lng]);
    }
    ?>
</div>