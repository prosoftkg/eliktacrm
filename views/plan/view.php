<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Plan */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Планировки'), 'url' => ['own']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-view">

    <p>
        <?= Html::a(Yii::t('app', 'Редактировать'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверенны что хотите удалить планировку?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= Html::img($model->getImageFile(), ['class' => 'object_img', 'style' => 'border:3px solid #ccc']); ?>
    <div class="right-view">
        <h1 class="minor_heading"><?= Html::encode($this->title) ?></h1>

        <div class="outer">
            <?php
            $rooms = unserialize($model->rooms);
            foreach ($rooms as $key => $val) : ?>
                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'><?= $key; ?></span>
                    <span class='object_field'><?= $val; ?>м²</span>
                </div>
            <?php endforeach; ?>

            <div class="general-rooms">
                Общее количество комнат - <?= $model->room_count; ?>
            </div>

            <div class="general-rooms">
                Ориентировочная стоимость - ? <?= $model->room_count; ?>
            </div>
        </div>
    </div>

</div>