<?php
use yii\helpers\Html;
?>
<div class="plan-block" style="width: 170px">
    <div class="clear plan-title"><?=Html::a($model->title,['view','id'=>$model->id]);?></div>
    <?= Html::a(Html::img($model->getThumbFile(),['class'=>'plan-pic']), $model->getImageFile(), ['rel' => 'fancybox']); ?>
</div>