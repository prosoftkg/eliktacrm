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


    <?= Html::img(Url::base() . '/images/object/' . $model->logo, ['class' => 'object_img']); ?>

    <div class="right-view">

        <h1 class="minor_heading"><?= Html::encode($this->title) ?></h1>

        <div class="outer">
            <div class="outer_container">
                <div class='filler'></div>
                <span class='object_label'>Срок: </span>
                <span class='object_field'><?= $model->due_quarter . " квартал " . $model->due_year; ?></span>
            </div>

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
                <?= Html::a(Html::img(Url::base() . '/images/building/s-' . $building->img, ['class' => 'portrait1']), ['/building/view', 'id' => $building->id]); ?>
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

    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2923.9429829832116!2d74.61112491439052!3d42.87404757915567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x389eb7c6fb55b4c3%3A0x519c6ea8206ecb5e!2z0JrQuNC10LLRgdC60LDRjywgNTgsIDU4IEtpZXYgU3QsIEJpc2hrZWs!5e0!3m2!1sen!2skg!4v1479361324219" width="100%" height="250" frameborder="0" style="border:0;" allowfullscreen></iframe>
</div>