<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Assignment;

/* @var $this yii\web\View */
/* @var $model app\models\Assignment */



$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="assignment-view">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <h1><?= $model->assignmentType($model->type); ?></h1>
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->assignmentStatus($model->status)
            ],
            [
                'attribute' => 'priority',
                'format' => 'raw',
                'value' => $model->assignmentPriority($model->priority)
            ],
            [
                'attribute' => 'periods',
                'format' => 'raw',
                'value' => "{$model->date_from} - {$model->date_to}"
            ],
            'description',
        ],
    ]) ?>

</div>
