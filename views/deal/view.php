<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Deal */

$this->title = $model->getDeal();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Сделки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать сделку',
    'url' => ['deal/update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Удалить сделку',
    'url' => ['deal/delete', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="deal-view">

    <h1 class="general_heading"><?= Html::encode($this->title) ?></h1>

    <?php
    $dataVar = "";
    if ($model->status == 1) {
        $model->dataType = 'Срок брони';
        $dataVar = "От " . $model->date_from . " до " . $model->date_to;
    } elseif ($model->status == 2) {
        $model->dataType = "Дата сделки";
        $dataVar = $model->deal_date;
    }
    ?>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'value' => $model->statusLabel($model->status)
            ],
            [
                'label' => $model->dataType,
                'value' => $dataVar,
            ],

            [
                'attribute' => 'Задаток',
                'value' => '$'.$model->client->prepay,
            ],
            [
                'attribute' => 'left_sum',
                'value' => '$'.($model->apartment->getPrice('dollar') - $model->client->prepay),
            ],
            /*[
                'attribute'=>'reference',
                //'value'=>$model->saleReference->title
            ],*/
            'text:ntext',
            [
                'attribute' => 'manager',
                'format' => 'raw',
                'value' => $model->profile->name
            ],
        ],
    ]) ?>

</div>
