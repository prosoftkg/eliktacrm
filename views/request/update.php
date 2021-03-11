<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = Yii::t('app', 'Редактировать запрос', [
    'modelClass' => 'Request',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Запросы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Редактировать');
?>
<div class="request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
