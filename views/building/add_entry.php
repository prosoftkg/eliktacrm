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

    <ul class="tab-margin entry-tabs nav nav-tabs">

        <?php
        if ($building_entry):
            foreach ($building_entry as $entry_num): ?>
                <li>
                    <a data-toggle="tab" href="#menu<?= $entry_num->number ?>">Подъезд #<?= $entry_num->number ?></a>
                </li>
            <? endforeach;
        endif; ?>
        <li>
            <a data-toggle="tab" href="#menu0">Добавить подъезд</a>
        </li>
    </ul>

    <div class="tab-content">


<?php
if ($building_entry):
    $prev_apartment_count = 0;
    foreach ($building_entry as $entry_num):
        $apartment_count = $entry_num->apartment_amount;
        $height = $model->stores_amount;
        $floor = $model->stores_amount;
        $roomString = "";
        $result = "";
        $stepY = 1;
        $allApartments = \yii\helpers\ArrayHelper::index(\app\models\Apartment::find()->where(['building_id' => $model->id, 'entry_id' => $entry_num->id])->orderBy(['id' => SORT_DESC])->all(), 'number');
        echo
            "<div class='flat-wrap tab-pane fade' id=menu" . $entry_num->number . ">
                        <div class='flat_aligner'>";
        $apartment_count = $entry_num->apartment_amount;
        $result = "";
        for ($heightStep = $height - 1; $heightStep >= 0; $heightStep--) {
            $result .= "<div class='clear'></div>";
            $result .= "<div class='floor_num'>" . $floor . "</div>";
            for ($apartment = 1; $apartment <= $apartment_count; $apartment++) {
                $index = $heightStep * $apartment_count + $apartment + $prev_apartment_count;
                if (isset($allApartments[$index]->plan)) {
//                    $somPrice = $model->object->base_som_price * $allApartments[$index]->plan->area;
//                    $dollarPrice = $model->object->base_dollar_price * $allApartments[$index]->plan->area;
                    $somPrice = $allApartments[$index]->getPrice('som');
                    $dollarPrice = $allApartments[$index]->getPrice('dollar');
                    $planData = "
                                <span class='room_amount' data-number=" . $allApartments[$index]->number . " data-toggle='modal' data-target='#userModal' data-id = " . $allApartments[$index]->id . " >" . $allApartments[$index]->plan->room_count . "</span>
                                <span class='apart_area'>S = " . $allApartments[$index]->plan->area . "</span>
                                <span class='apart_price'>" . number_format($somPrice, 0, '.', ' ') . " сом</span>
                                <span class='dollar_price'>$" . number_format($dollarPrice, 0, '.', ' ') . "</span>";

                } else {
                    $planData = "";
                }
                $result .=
                    "
                <div flatNum='$index' buildingId='$model->id' entryNum='$entry_num->number' id='{$allApartments[$index]->id}' status='{$allApartments[$index]->status}' class='status-{$allApartments[$index]->status} flat_square'>
                <span class='apart_num'>#" . $allApartments[$index]->number . "</span>
                    {$planData}
                </div>";

            }
            $floor--;
        }
        echo $result; ?>
        </div>
        </div>
        <?php
        $prev_apartment_count += $height * $apartment_count;
    endforeach;


    ?>
    <div class="form_block_right">
        <div class='tooltip-text' entry="<?= $entry_num->number ?>" building="<?= $model->id; ?>"
             id="tooltip-text-<?= $entry_num->number ?>">
            <?php
            $auth = Yii::$app->authManager;
            if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin || $auth->getAssignment('owner', Yii::$app->user->id))):?>
                <?= Html::a('Бронировать', '/', ['class' => 'btn-margin btn button-option button-book', 'status-id' => '1']); ?>
                <?= Html::a('Планировка', '#', ['class' => 'btn-margin btn button-option button-plan', 'status-id' => '4', 'onClick' => "showContent('/apartment/plan');return false"]); ?>
                <?= Html::a('Сделка завершена', '/', ['class' => 'btn-margin btn button-option button-sold ', 'status-id' => '3']); ?>
                <?= Html::a('Резерв', '#', ['class' => 'btn-margin btn button-reserve button-option', 'status-id' => '2', 'onClick' => "showContent('/apartment/reserve');return false"]); ?>
                <?= Html::a('Вернуть в продажу', '#', ['class' => 'btn-margin btn button-option button-return', 'status-id' => '5', 'onClick' => "showContent('/apartment/return');return false"]); ?>
                <?= Html::a('Корректировка цен', '/', ['class' => 'btn-margin btn button-option button-price', 'status-id' => '5']); ?>
                <?= Html::a('Назначить агента', '#', ['class' => 'btn-margin btn button-option button-agent', 'status-id' => '5', 'onClick' => "showContent('/apartment/assignAgent');return false"]); ?>
                <?=
                Html::a('Коммерческое предложение', '#', ['class' => 'btn-margin btn button-option button-commercial', 'status-id' => '5', 'onClick' => "showContent('/apartment/commercial');return false"]);


            elseif (!Yii::$app->user->isGuest && ($auth->getAssignment('manager', Yii::$app->user->id))):?>
                <?= Html::a('Бронировать', '/', ['class' => 'btn-margin btn button-option button-book', 'status-id' => '1']); ?>
                <?= Html::a('Сделка завершена', '/', ['class' => 'btn-margin btn button-option button-sold ', 'status-id' => '3']); ?>
                <?= Html::a('Коммерческое предложение', '#', ['class' => 'btn-margin btn button-option button-commercial', 'status-id' => '5', 'onClick' => "showContent('/apartment/commercial');return false"]); ?>
                <?=
                Html::a('Вернуть в продажу', '#', ['class' => 'btn-margin btn button-option button-return', 'status-id' => '5', 'onClick' => "showContent('/apartment/return');return false"]);

            endif;
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
<?

Modal::begin([
    'header' => "<h2>Квартира № <span id='modal_number'></span></h2>",
    'id' => 'userModal',
    //'toggleButton' => ['class' => 'room_amount'],
]);

Modal::end();
Pjax::end();
?>