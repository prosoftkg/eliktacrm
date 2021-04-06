<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\Task */
?>
<div id="chatModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Write a message') ?></h4>
            </div>
            <div class="modal-body">
                <?= Html::beginForm('/chat/create-modal'); ?>
                <?= Html::label('Сообщение', 'chat-text', ['class' => 'control-label']); ?>
                <?= Html::textarea('text', null, ['class' => 'form-control js_modal_chat', 'id' => 'chat-text']); ?>
                <?= Html::hiddenInput('receiver_id', $model->object->company->owner_id, [
                    //'class' => 'js_chat_receiver_id'
                ]); ?>
                <?= Html::hiddenInput('subject', $model->plan->room_count . ' - ком. кв. ЖК ' . $model->object->title . '; ' . $model->object->company->name); ?>
                <?= Html::hiddenInput('view', Yii::$app->controller->id); ?>
                <?= Html::hiddenInput('view_id', $model->id); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                <button class="btn btn-primary" type="submit"><?= Yii::t('app', 'Send') ?></button>
            </div>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>