<?php
use yii\helpers\Html;
?>
<div class="sked-index">
    <div class="search-result">
        <div class="result-wrapper">
            <div class="list-apart-title"><?= Html::a('Кварира #' . $model->number, ['/apartment/view', 'id' => $model['id']]); ?></div>
            <div class="list-apart-object">Объект: <?= $model->object->title; ?></div>
            <div class="list-apart-building"><?= $model->building->title; ?></div>
            <div class="list-apart-price">Цена: <?= $model->getPrice('dollar'); ?></div>
            <div class="list-apart-area">Площадь: <?= $model->plan->area; ?> м²</div>
            <div class="list-apart-floor">Этаж: <?= $model->floor; ?></div>
        </div>
    </div>
</div>