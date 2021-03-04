<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Object;
use app\models\Company;
use app\models\Plan;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ApartmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Apartments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apartment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    $objects = Object::find()
        ->andFilterWhere([
            'in', 'company_id', ArrayHelper::merge(
                [Yii::$app->user->identity->company_id],
                ArrayHelper::getColumn(Company::find()->andFilterWhere(['owner_id' => Yii::$app->user->id])->select('id')->all(), 'id')
            )])->all();
    ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Объект',
                'value' => 'brand',
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'object_id',
                        ArrayHelper::map($objects, 'id', 'title'),
                        ['class' => 'form-control', 'prompt' => 'Все объекты']
                    ),
                'contentOptions' => ['class' => 'object-title-content'],
                'headerOptions' => ['class' => 'object-title-header']
            ],
            'floor',
            [
                'attribute' => 'plan_id',
                'value' => 'plan.room_count',
                'label' => 'Кол-во комнат'
            ],
            [
                'attribute' => 'area',
                'value' => function ($model) {
                        return $model->plan->area . " м2";
                    },
                'label' => 'Площадь'
            ],
            [
                'attribute' => 'dollar_price',
                'value' => function ($model) {
                        return "$".$model->getPrice('dollar');
                    },
                'label' => 'Цена'
            ],
            //'aroom.room_count',
            // 'building_id',

        ],
    ]); ?>
</div>
