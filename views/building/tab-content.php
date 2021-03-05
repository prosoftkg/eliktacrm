<?php

/**
 * @var yii\web\View $this
 * @var app\models\Entry $mdlEntry
 */

use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

?>
<div class="flat_aligner">
    <div class="chess-block">
        <?php
        /**
         * Apartments within the entry indexed by apartment number
         * @var Apartment[] $apartments
         */
        $apartments = ArrayHelper::index($mdlEntry->apartments, 'number');
        # Sort apartments descending
        ArrayHelper::multisort($apartments, 'number', SORT_ASC);
        for ($floor = $mdlEntry->building->stores_amount; $floor > 0; $floor--) {

            # Show floor number
            echo Html::tag('div', null, ['class' => 'clear']);
            echo Html::tag('div', $floor, ['class' => 'floor_num', 'number' => $floor]);

            /**
             * Apartments within the floor
             * @var Apartment[] $floor_apartments
             */
            $floor_apartments = array_slice($apartments, ($floor - 1) * $mdlEntry->apartment_amount, $mdlEntry->apartment_amount);
            ArrayHelper::multisort($floor_apartments, 'number', SORT_ASC);

            foreach ($floor_apartments as $mdlApartment) {
                $status = $mdlApartment->status ?? 0;
                echo Html::beginTag('div', [
                    'class' => "flat_square status-{$status}",
                    'flatnum' => $mdlApartment->number,
                    'buildingid' => $mdlEntry->building_id,
                    'entrynum' => $mdlEntry->number,
                    'id' => $mdlApartment->id,
                    'status' => $status,
                    'floor' => $floor
                ]);
                echo Html::tag('span', '#' . $mdlApartment->number, ['class' => 'apart_num']);
                if ($mdlApartment->plan_id) {
                    echo Html::tag('span', $mdlApartment->plan->room_count, [
                        'class' => 'room_amount',
                        'data' => [
                            'number' => $mdlApartment->number,
                            'toggle' => 'modal',
                            'target' => '#userModal',
                            'id' => $mdlApartment->id,
                        ]
                    ]);
                    echo Html::tag('span', "S = {$mdlApartment->plan->area}", ['class' => 'apart_area']);
                    echo Html::tag('span', number_format($mdlApartment->getPrice('som'), 0, '.', ' ') . ' сом', ['class' => 'apart_price']);
                    echo Html::tag('span', '$' . number_format($mdlApartment->getPrice('dollar'), 0, '.', ' '), ['class' => 'dollar_price']);
                }
                echo Html::endTag('div');
            }
        } ?>

    </div>
    <?php
    $auth = Yii::$app->authManager;
    if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin || $auth->getAssignment('owner', Yii::$app->user->id))) : ?>
        <div class="clear-delete-button">
            <?php
            echo Html::tag('div', 'Удалить подъезд', ['class' => 'btn btn-danger button-delete-entry', 'style' => 'clear:both']);
            ?>
        </div>
    <?php endif; ?>
</div>