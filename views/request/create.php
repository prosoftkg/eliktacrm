<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Request */

$this->title = Yii::t('app', 'Добавить запрос');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Запросы'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
