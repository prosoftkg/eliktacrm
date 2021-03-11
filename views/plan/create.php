<?php

use yii\helpers\Html;
use yii\bootstrap\Alert;


/* @var $this yii\web\View */
/* @var $model app\models\Plan */

$this->title = Yii::t('app', 'Добавить план');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if (Yii::$app->user->identity->company_id) {
        echo $this->render('_form', [
            'model' => $model
        ]);
    } else {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-warning',
            ],
            'body' => 'Вы ещё не <a href="/company/create">добавили компанию</a>!',
        ]);
    }
    ?>
</div>