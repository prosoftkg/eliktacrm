<?php
use yii\widgets\ActiveForm;
use kartik\slider\Slider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Objects;
use app\models\Company;
use app\models\Plan;
use yii\grid\GridView;

?>


<div class="apartment-index">
    <h1 class="general_heading">Поиск квартир</h1>

    <div class="form-group rel">
        <div class="form-group range-slider-wrap">
            <?= Html::beginForm(['apartment/selector'], 'get'); ?>

            <div class="left-apart-block">
                <div class="range-cover">
                    <label class="control-label">Цена($)</label>
                    <div class="clear"></div>
                    <?= Html::input('text', 'price_min', '', ['class' => 'form-control shorten_input', 'placeholder' => $price_min_val]); ?>
                    <label class="minor-label">-</label>
                    <?= Html::input('text', 'price_max', '', ['class' => 'form-control shorten_input', 'placeholder' => $price_max_val]); ?>
                </div>

                <div class="clear"></div>

                <div class="range-cover">
                    <label class="control-label">Площадь(М²)</label>
                    <div class="clear"></div>
                    <?= Html::input('text', 'area_min', '', ['class' => 'form-control shorten_input', 'placeholder' => $area_min]); ?>
                    <label class="minor-label">-</label>
                    <?= Html::input('text', 'area_max', '', ['class' => 'form-control shorten_input','placeholder' => $area_max]); ?>
                </div>
            </div>

            <div class="range-cover">
                <label class="control-label">Этаж</label>
                <div class="clear"></div>
                <?=
                Slider::widget([
                    'name' => 'floor',
                    'sliderColor' => Slider::TYPE_PRIMARY,
                    'value' => $floor_range,
                    'pluginOptions' => [
                        'min' => 1,
                        'max' => 25,
                        'width' => '400',
                        'ticks' => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25],
                        'ticks_labels' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25'],
                        'step' => 1,
                        'range' => true,
                    ]]);
                ?>
            </div>

            <div class="range-cover room_range_slider">
                <label class="control-label">Количество комнат</label>
                <div class="clear"></div>
                <?=
                Slider::widget([
                    'name' => 'rooms',
                    'sliderColor' => Slider::TYPE_PRIMARY,
                    'value' => $room_range,
                    'pluginOptions' => [
                        'min' => 1,
                        'max' => 8,
                        'width' => '400',
                        'ticks' => [1, 2, 3, 4, 5, 6, 7, 8],
                        'ticks_labels' => ['1', '2', '3', '4', '5', '6', '7', '8'],
                        'step' => 1,
                        'range' => true,
                    ]]);
                ?>
            </div>

            <div class="clear"></div>
            <?= Html::submitButton('Поиск', ['class' => 'btn apart-selector btn-primary']); ?>
            <?= Html::endForm(); ?>
        </div>
    </div>

    <div class="clear"></div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    $objects = Objects::find()
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
            [
                'attribute' => 'number',
                'format' => 'raw',
                'value' => function ($model) {
                        return Html::a("Квартира №" . $model->number, ['view', 'id' => $model->id]);
                    },
                'label' => 'Номер квартиры',
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
                        return "$" . $model->getPrice('dollar');
                    },
                'label' => 'Цена'
            ],
            //'aroom.room_count',
            // 'building_id',

        ],
    ]); ?>
</div>
