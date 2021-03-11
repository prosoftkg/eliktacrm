<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Компании', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = [
    'label' => 'Добавить менеджеров',
    'url' => ['user/admin/useradd', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Добавить объект',
    'url' => ['object/create', 'company' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Удалить',
    'url' => ['company/delete', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
$this->params['breadcrumbs'][] = [
    'label' => 'Редактировать',
    'url' => ['company/update', 'id' => $model->id],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="company-view">

    <?php //if(!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin):
    ?>


    <?php
    if ($model->img)
        echo Html::img(Url::base() . '/images/company/' . $model->img, ['class' => 'company_logo']);
    echo Html::tag('div', $model->name, ['class' => 'list-object-title']);
    echo Html::tag('div', $model->phone, ['class' => 'company-view-item']);
    echo Html::tag('div', $model->address, ['class' => 'company-view-item']);
    echo Html::tag('div', $model->email, ['class' => 'company-view-item']);

    //php endif;
    ?>


</div>