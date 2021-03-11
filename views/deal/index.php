<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Deal;
use yii\helpers\ArrayHelper;
use app\models\Objects;
use app\models\Company;
use app\models\User;
use app\models\Profile;
use app\models\Reference;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Сделки');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-index">

    <h1 class="general_heading">Сделки компании</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $objects = Objects::find()
        ->andFilterWhere([
            'in', 'company_id', ArrayHelper::merge(
                [Yii::$app->user->identity->company_id],
                ArrayHelper::getColumn(Company::find()->andFilterWhere(['owner_id' => Yii::$app->user->id])->select('id')->all(), 'id')
            )
        ])
        ->all();


    $users = User::find()
        ->andFilterWhere(['id' => Yii::$app->user->id])
        ->orWhere([
            'in', 'company_id',
            ArrayHelper::getColumn(Company::find()->andFilterWhere(['owner_id' => Yii::$app->user->id])->select('id')->all(), 'id')
        ])
        ->all();

    $reference = Reference::find()
        ->asArray()
        ->all();
    ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => false,
        'columns' => [
            [
                'attribute' => 'Сделка',
                'value' => 'deal',
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'object_id',
                        ArrayHelper::map($objects, 'id', 'title'),
                        ['class' => 'form-control', 'prompt' => 'Все объекты']
                    ),
                'contentOptions' => ['class' => 'object-title-content'],
                'headerOptions' => ['class' => 'object-title-header']
            ],

            [
                'format' => 'html',
                'attribute' => 'Клиент',
                'value' => function ($model) {
                        return Html::a($model->client->fullname,['/client/view','id'=>$model->client_id])."<br>".$model->client->phone;
                    },
            ],

            'value' => 'deal_date',
            [
                'attribute' => 'reference',
                'value' => 'saleReference.title',
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'reference',
                        ArrayHelper::map($reference, 'id', 'title'),
                        ['class' => 'form-control', 'prompt' => 'Все каналы']
                    ),
                'contentOptions' => ['class' => 'object-title-content'],
                'headerOptions' => ['class' => 'object-title-header']
            ],

            [
                'attribute' => 'Менеджер',
                'value' => 'profile.name',
                'filter' => Html::activeDropDownList(
                        $searchModel,
                        'profile_id',
                        ArrayHelper::map($users, 'profile.user_id', 'profile.name'),
                        ['class' => 'form-control', 'prompt' => 'Все менеджеры']
                    ),
                'contentOptions' => ['class' => 'object-title-content'],
                'headerOptions' => ['class' => 'object-title-header']
            ],

            [
                'attribute' => 'status',
                //'filter'=>array(Deal::STATUS_DEAL_BOOKED =>"Бронирован",Deal::STATUS_DEAL_SOLD =>"Завершен"),
                'value' => function ($model) {
                        if ($model->status == Deal::STATUS_DEAL_BOOKED)
                            return "Бронирован";
                        else if ($model->status == Deal::STATUS_DEAL_SOLD)
                            return "Продан";
                    }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
