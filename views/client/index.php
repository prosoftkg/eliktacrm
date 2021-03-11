<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'База клиентов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1 class="general_heading"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'fullname',
            'phone',
            'phone2',
            //'birthday',
            // 'passport_num',
            // 'email:email',
            // 'address',
            // 'apartment_id',
            // 'prepay',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
