<?php

use yii\helpers\Html;

$plans = \app\models\Plan::find()->where(['owner_id' => Yii::$app->user->id])->all();
?>

<div class="plan-wrapper">
    <?php
    foreach ($plans as $plan) : ?>
        <div class="plan-block">
            <div class="clear plan-title"><?= $plan->title; ?></div>
            <?= Html::a(Html::img($plan->getThumbFile()), $plan->getImageFile(), ['rel' => 'fancybox']); ?>
            <div class="clear plan-select" id="<?= $plan->id; ?>">Выбрать</div>
        </div>
    <?php endforeach ?>
</div>

<div class="clear"></div>

<div class="plan_send btn btn-success">Применить</div>
<div class="add_plans btn btn-primary"><?= Html::a('Добавить новые планы', ['plan/create'], ['style' => 'color:#fff', '_target' => 'blank']) ?></div>