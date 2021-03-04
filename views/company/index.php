<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->img) {
                        return Html::img(Url::base() . '/images/company/' . $model->img, ['class' => 'w100px']);
                    }
                    return null;
                }
            ],
            'name',
            'phone',
            'address',
            'email:email',
            [
                'attribute' => 'owner_id',
                'value' => function ($model) {
                    if ($model->owner) {
                        return $model->owner->username;
                    }
                    return $model->owner_id;
                }
            ],
            // 'img',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>