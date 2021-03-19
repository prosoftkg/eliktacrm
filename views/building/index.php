<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BuildingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Buildings');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="building-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Building'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'object_id',
            'title',
            'img',
            'address',
            'due_quarter',
            'due_year',
            // 'description',
            // 'stores_amount',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>