<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Запросы');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Добавить запрос',
    'url' => ['request/create'],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
?>
<div class="request-index">

    <h1 class="general_heading"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            [
                'label' => 'Тип Запроса',
                'value'=>function($model)
                {
                    if($model->type == 1)
                        return "Бартер";
                    elseif ($model->type == 2)
                        return "Задержка";
                    else
                        return "Другое";
                }
            ],
            'discount',
            'period',
            // 'other',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);


    ?>
</div>
