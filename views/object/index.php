<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Objects;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * User: Damir
 * Date: 9/30/16
 * Time: 3:08 AM
 */

use yii\widgets\ListView;

$this->title = Yii::t('app', 'Объекты');
$this->params['breadcrumbs'][] = $this->title;
$dao = Yii::$app->db;
$companies = $dao->createCommand("SELECT * FROM `company`")->queryAll();
$companies = ArrayHelper::map($companies, 'id', 'name');
?>

<?php echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'id',
        [
            'attribute' => 'logo',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::img(Url::base() . '/images/object/' . $model->logo, ['class' => 'w100px']);;
            },
            'filter' => false,

        ],
        'title',
        'base_dollar_price',
        'base_som_price',
        [
            'attribute' => 'city',
            'value' => function ($model) {
                return Objects::$cities[$model->city];
            },
            'filter' => Objects::$cities,

        ],
        [
            'attribute' => 'company_id',
            'value' => function ($model) {
                return $model->company->name;
            },
            'filter' => $companies,

        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]);
?>