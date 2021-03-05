<?php

/**
 * @var $this \yii\web\View
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use app\models\Entry;
use yii\widgets\Pjax;
use yii\bootstrap\Tabs;
use yii\bootstrap\Modal;
use app\assets\core\Asset;
use yii\helpers\ArrayHelper;
use app\models\Apartment;

Asset::register($this);
/** NOTE: THIS FILE IS NOT USED, LOOK add_entry2.php */
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
    echo Html::beginTag('ul', ['class' => 'tab-margin entry-tabs nav nav-tabs']);
    if ($building_entry) {
        foreach ($building_entry as $entry_num) {
            echo Html::tag('li', Html::a('Подъезд #' . $entry_num->number, '#menu' . $entry_num->number, ['data-toggle' => 'tab']));
        }
    }
    echo Html::tag('li', Html::a('Добавить подъезд', '#menu0', ['data-toggle' => 'tab']));
    echo Html::endTag('ul');
    ?>

    <div class="tab-content">
        <?php
        if ($building_entry) :
            $prev_apartment_count = 0;
            foreach ($building_entry as $entry_num) {
                $apartment_count = $entry_num->apartment_amount;
                $height = $model->stores_amount;
                $floor = $model->stores_amount;
                $roomString = "";
                $result = "";
                $stepY = 1;
                $allApartments = ArrayHelper::index(Apartment::find()->where(['building_id' => $model->id, 'entry_id' => $entry_num->id])->orderBy(['id' => SORT_DESC])->all(), 'number');
                echo Html::beginTag('div', ['class' => 'flat-wrap tab-pane fade', 'id' => 'menu' . $entry_num->number]); //div1
                echo Html::beginTag('div', ['class' => 'flat_aligner']); //div2
                $apartment_count = $entry_num->apartment_amount;
                $result = "";
                for ($heightStep = $height - 1; $heightStep >= 0; $heightStep--) {
                    $result .= Html::tag('div', '', ['class' => 'clear']);
                    $result .= Html::tag('div', $floor, ['class' => 'floor_num']);
                    for ($apartment = 1; $apartment <= $apartment_count; $apartment++) {
                        $index = $heightStep * $apartment_count + $apartment + $prev_apartment_count;
                        if (isset($allApartments[$index]->plan)) {
                            //                    $somPrice = $model->object->base_som_price * $allApartments[$index]->plan->area;
                            //                    $dollarPrice = $model->object->base_dollar_price * $allApartments[$index]->plan->area;
                            $somPrice = $allApartments[$index]->getPrice('som');
                            $dollarPrice = $allApartments[$index]->getPrice('dollar');
                            $planData = Html::tag('span', $allApartments[$index]->plan->room_count, ['class' => 'room_amount', 'data-number' => $allApartments[$index]->number, 'data-toggle' => 'modal', 'data-target' => '#userModal', 'data-id' => $allApartments[$index]->id]);
                            $planData .= Html::tag('span', 'S = ' . $allApartments[$index]->plan->area);
                            $planData .= Html::tag('span', number_format($somPrice, 0, '.', ' ') . ' сом', ['class' => 'apart_price']);
                            $planData .= Html::tag('span', '$' . number_format($dollarPrice, 0, '.', ' '), ['class' => 'dollar_price']);
                        } else {
                            $planData = "";
                        }
                        $result .= Html::tag('div', Html::tag('span', '#' . $allApartments[$index]->number, ['class' => 'apart_num']) . $planData);
                    }
                    $floor--;
                }
                echo $result;
                echo Html::endTag('div'); //div2
                echo Html::endTag('div'); //div1
                $prev_apartment_count += $height * $apartment_count;
            }


        ?>
            <div class="form_block_right">
                <div class='tooltip-text' entry="<?= $entry_num->number ?>" building="<?= $model->id; ?>" id="tooltip-text-<?= $entry_num->number ?>">
                    <?php
                    $auth = Yii::$app->authManager;
                    if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin || $auth->getAssignment('owner', Yii::$app->user->id))) {
                        echo Html::a('Бронировать', '/', ['class' => 'btn-margin btn button-option button-book', 'status-id' => '1']);
                        echo Html::a('Планировка', '#', ['class' => 'btn-margin btn button-option button-plan', 'status-id' => '4', 'onClick' => "showContent('/apartment/plan');return false"]);
                        echo Html::a('Сделка завершена', '/', ['class' => 'btn-margin btn button-option button-sold ', 'status-id' => '3']);
                        echo Html::a('Резерв', '#', ['class' => 'btn-margin btn button-reserve button-option', 'status-id' => '2', 'onClick' => "showContent('/apartment/reserve');return false"]);
                        echo Html::a('Вернуть в продажу', '#', ['class' => 'btn-margin btn button-option button-return', 'status-id' => '5', 'onClick' => "showContent('/apartment/return');return false"]);
                        echo Html::a('Корректировка цен', '/', ['class' => 'btn-margin btn button-option button-price', 'status-id' => '5']);
                        echo Html::a('Назначить агента', '#', ['class' => 'btn-margin btn button-option button-agent', 'status-id' => '5', 'onClick' => "showContent('/apartment/assignAgent');return false"]);
                        echo
                        Html::a('Коммерческое предложение', '#', ['class' => 'btn-margin btn button-option button-commercial', 'status-id' => '5', 'onClick' => "showContent('/apartment/commercial');return false"]);
                    } else if (!Yii::$app->user->isGuest && ($auth->getAssignment('manager', Yii::$app->user->id))) {
                        echo Html::a('Бронировать', '/', ['class' => 'btn-margin btn button-option button-book', 'status-id' => '1']);
                        echo Html::a('Сделка завершена', '/', ['class' => 'btn-margin btn button-option button-sold ', 'status-id' => '3']);
                        echo Html::a('Коммерческое предложение', '#', ['class' => 'btn-margin btn button-option button-commercial', 'status-id' => '5', 'onClick' => "showContent('/apartment/commercial');return false"]);
                        echo Html::a('Вернуть в продажу', '#', ['class' => 'btn-margin btn button-option button-return', 'status-id' => '5', 'onClick' => "showContent('/apartment/return');return false"]);
                    }
                    ?>
                </div>


                <div id='load-content'>

                </div>

                <div id='loading' style='display: none'>
                    Идет загрузка...
                </div>

            </div>
        <?php endif; ?>
        <div class="flat-wrap tab-pane fade" id="menu0">
            <?php $entry = new Entry();
            $form = ActiveForm::begin([
                'id' => 'orders-form',
                'action' => Url::to(['entry/create']),
                //'validationUrl' => Url::to(['orders/validate']),
                //'enableClientValidation' => true,
                //'enableAjaxValidation' => true,
                'options' => [
                    'class' => 'orders-form-gq',
                    'data-pjax' => true
                ]
            ]); ?>

            <?= $form->field($entry, 'building_id')->hiddenInput(['value' => $building_id])->label(false); ?>
            <?= Html::hiddenInput('stores', $model->stores_amount); ?>

            <?= $form->field($entry, 'number')->textInput(); ?>

            <?= $form->field($entry, 'apartment_amount')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Добавить'), ['class' => 'btn btn-primary append_entry']) ?>
            </div>

            <?php
            ActiveForm::end(); ?>
        </div>
    </div>

</div>
<?php

Modal::begin([
    'header' => "<h2>Квартира № <span id='modal_number'></span></h2>",
    'id' => 'userModal',
    //'toggleButton' => ['class' => 'room_amount'],
]);

Modal::end();
Pjax::end();
?>