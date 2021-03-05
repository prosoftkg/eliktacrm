<?php

/**
 * @var $this \yii\web\View
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;
use yii\bootstrap\Modal;
use app\assets\core\Asset;
use yii\helpers\ArrayHelper;

Asset::register($this);
?>
<div class="orders-form">
    <?php
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


    Pjax::begin(['enablePushState' => true, 'id' => 'reload_block']);
    ?>

    <div class="clear"></div>
    <?php
    $auth = Yii::$app->authManager;
    $identityArr = [];
    if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin || $auth->getAssignment('owner', Yii::$app->user->id))) {
        $identityArr = [[
            'label' => 'Добавить подъезд',
            'content' => $this->render('add_new_entry', ['mdlBuilding' => $mdlBuilding]),
            'options' => ['class' => 'disable-form'],
        ]];
    }
    echo TabsX::widget([
        'enableStickyTabs' => true,
        'items' => ArrayHelper::merge(array_map(function ($mdlEntry) {
            return [
                'label' => 'Подъезд №' . $mdlEntry->number,
                'content' => $this->render('tab-content', ['mdlEntry' => $mdlEntry]),
                'options' => ['entry_id' => $mdlEntry->id],
            ];
        }, $mdlBuilding->entry), $identityArr),
        'options' => ['class' => 'tab-margin']
    ]); ?>

    <?php
    echo Html::beginTag('div', ['class' => 'form_block_right']);
    echo Html::beginTag('div', [
        'class' => 'tooltip-text',
    ]);

    if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin || $auth->getAssignment('owner', Yii::$app->user->id))) : ?>
        <?= Html::tag('span', 'Бронировать', ['class' => 'btn-margin btn button-option button-book', 'status-id' => '1']); ?>
        <?= Html::a('Планировка', '#', ['class' => 'btn-margin btn button-option button-plan', 'status-id' => '4', /* 'onClick' => "showContent('/apartment/plan');return false" */]); ?>
        <?= Html::tag('span', 'Сделка завершена', ['class' => 'btn-margin btn button-option button-sold ', 'status-id' => '3']); ?>
        <?= Html::a('Резерв', '#', ['class' => 'btn-margin btn button-reserve button-option', 'status-id' => '2', /* 'onClick' => "showContent('/apartment/reserve');return false" */]); ?>
        <?= Html::a('Вернуть в продажу', '#', ['class' => 'btn-margin btn button-option button-return', 'status-id' => '5', /* 'onClick' => "showContent('/apartment/return');return false" */]); ?>
        <?= Html::tag('span', 'Корректировка цен', ['class' => 'btn-margin btn button-option button-price', 'status-id' => '5']); ?>
        <?= Html::a('Назначить агента', '#', ['class' => 'btn-margin btn button-option button-agent', 'status-id' => '5', /* 'onClick' => "showContent('/apartment/assignAgent');return false" */]); ?>
    <?=
        Html::tag('span', 'Коммерческое предложение', ['class' => 'btn-margin btn button-option button-commercial', 'status-id' => '5']);


    elseif (!Yii::$app->user->isGuest && ($auth->getAssignment('manager', Yii::$app->user->id))) : ?>
        <?= Html::tag('span', 'Бронировать', ['class' => 'btn-margin btn button-option button-book', 'status-id' => '1']); ?>
        <?= Html::tag('span', 'Сделка завершена', ['class' => 'btn-margin btn button-option button-sold ', 'status-id' => '3']); ?>
        <?= Html::tag('span', 'Коммерческое предложение', ['class' => 'btn-margin btn button-option button-commercial', 'status-id' => '5']); ?>
    <?=
        Html::a('Вернуть в продажу', '#', ['class' => 'btn-margin btn button-option button-return', 'status-id' => '5']);

    endif;
    echo Html::endTag('div');
    echo Html::tag('div', '', ['id' => 'load-content']);
    echo Html::tag('div', 'Идет загрузка...', ['id' => 'loading', 'style' => 'display:none']);
    echo Html::endTag('div');

    Modal::begin([
        'header' => "<h2>Квартира № <span id='modal_number'></span></h2>",
        'id' => 'userModal',
        //'toggleButton' => ['class' => 'room_amount'],
    ]);
    Modal::end();
    Pjax::end();
    ?>