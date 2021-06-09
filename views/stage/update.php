<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Stage */

$this->title = 'Редактировать: ' . $model->id;
?>
<div class="stage-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>