<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Reference */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Канал продаж'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Удалить',
    'url' => ['reference/delete','id'=>$model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n",
    'data' => [
        'confirm' => Yii::t('app', 'Вы уверены что хотите удалить'),
        'method' => 'post',
    ],
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать',
    'url' => ['reference/update','id'=>$model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="reference-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
        ],
    ]) ?>

</div>
