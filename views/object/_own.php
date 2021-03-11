<?php
/**
 * Created by PhpStorm.
 * User: Damir
 * Date: 9/30/16
 * Time: 2:43 AM
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="object_index_left">
    <div class="object-intro">
        <div class="object_index_label">Жилой комплекс</div>
        <?= Html::a($model->title, ['/object/view', 'id' => $model->id], ['class' => 'list-object-title']); ?>
    </div>
    <div class="area_price">
        <div class="area_row">
            <ol class="ol_circle">
                <li><span class="left_ol">120</span><span class="right_ol">$29 000 - $35 000</span></li>

                <li><span class="left_ol">50</span><span class="right_ol">$45 000 - $75 000</span></li>

                <li><span class="left_ol">30</span><span class="right_ol">$30 000 - $70 000</span></li>
            </ol>
        </div>
    </div>
</div>
<div class="object_index_right">
    <?= Html::img(Url::base() . '/images/object/' . $model->logo, ['class' => 'object_index_pic']); ?>
</div>
