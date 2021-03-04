<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Планировки');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Добавить планировку',
    'url' => ['plan/create'],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];
echo newerton\fancybox\FancyBox::widget([
'target' => 'a[rel=fancybox]',
'helpers' => true,
'mouse' => true,
'config' => [
'maxWidth' => '90%',
'maxHeight' => '90%',
'playSpeed' => 7000,
'padding' => 0,
'fitToView' => false,
'width' => '70%',
'height' => '70%',
'autoSize' => false,
'closeClick' => false,
'openEffect' => 'elastic',
'closeEffect' => 'elastic',
'prevEffect' => 'elastic',
'nextEffect' => 'elastic',
'closeBtn' => false,
'openOpacity' => true,
'helpers' => [
'title' => ['type' => 'float'],
'buttons' => [],
'thumbs' => ['width' => 68, 'height' => 50],
'overlay' => [
'css' => [
'background' => 'rgba(0, 0, 0, 0.8)'
],
'locked' => false
]
],
]
]);
?>
<div class="plan-index">

    <h1 class="general_heading">
        <?= Html::encode($this->title) ?></h1>
    <?php echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'summary' => false
    ]); ?>
</div>
