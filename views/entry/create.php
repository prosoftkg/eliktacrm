<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Entry */

$this->title = Yii::t('app', 'Create Entry');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entry-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
