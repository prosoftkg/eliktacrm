<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Plan */

$this->title = Yii::t('app', 'Update', [
    'modelClass' => 'Plan',
]) . " план ".$model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Планировки'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="plan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
