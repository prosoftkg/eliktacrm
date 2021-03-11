<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Object */

$this->title = Yii::t('app', 'Редактировать : ', [
    'modelClass' => 'Object',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Объект'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="object-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
