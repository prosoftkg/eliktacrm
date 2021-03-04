<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Plan */

$this->title = Yii::t('app', 'Добавить план');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
