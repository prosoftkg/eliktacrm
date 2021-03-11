<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Reference */

$this->title = Yii::t('app', 'Добавить канал продаж');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Каналы продаж'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reference-create">

    <h1 class="general_heading"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
