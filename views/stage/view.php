<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Stage */

$this->title = date('d.m.Y', $model->date_stage);
$this->params['breadcrumbs'][] = ['label' => $model->building->object->title, 'url' => ['/object/' . $model->building->object->id]];
$this->params['breadcrumbs'][] = ['label' => $model->building->title, 'url' => ['/building/' . $model->building->id]];
$this->params['breadcrumbs'][] = 'Ход строительства ' . $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать',
    'url' => ['update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Удалить',
    'url' => ['delete', 'id' => $model->id],
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить?',
        'method' => 'post',
    ],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];

\yii\web\YiiAsset::register($this);
?>
<div class="stage-view">

    <h1><?= $this->title ?></h1>
    <?php
    echo Html::tag('div', $model->description);
    $imgs = explode(';', $model->img);
    foreach ($imgs as $img) {
        echo Html::img("@web/images/stage/" . $model->id . "/s_" . $img, ['style' => 'width:100px; margin-right:10px;']);
    }
    ?>

</div>