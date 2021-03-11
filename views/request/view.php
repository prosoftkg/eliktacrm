<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Запросы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Удалить',
    'url' => ['request/delete', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n",
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        'method' => 'post',
    ],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать',
    'url' => ['request/update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="request-view">

    <h1 class="general_heading"><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'discount',
            'period',
        ],
    ]) ?>

</div>
