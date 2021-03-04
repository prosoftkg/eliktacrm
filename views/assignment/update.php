<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assignment */

$this->title = Yii::t('app', 'Редактировать задачу: ', [
    'modelClass' => 'Assignment',
]) . $model->assignmentType($model->type);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->assignmentType($model->type), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="assignment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
