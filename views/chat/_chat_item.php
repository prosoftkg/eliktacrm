<?php

use yii\helpers\Html;

/* @var $model common\models\Chat*/
/* @var $delete */
/* @var $js_archive */


$online_status = 'offline';
$user_id = Yii::$app->user->id;
if ($model->sender_id == Yii::$app->user->id) {
    $receiver_name = $model->receiver->name;
    $receiver_id = $model->receiver_id;
    $receiver_key = $model->receiver->auth_key;
    /* if ($model->receiver->is_online && $receiver_id != $user_id) {
        $online_status = 'online';
    } */
} else {
    $receiver_name = $model->sender->name;
    $receiver_id = $model->sender_id;
    $receiver_key = $model->sender->auth_key;
    /* if ($model->sender->is_online && $receiver_id != $user_id) {
        $online_status = 'online';
    } */
}
$subjs = explode(';', $model->subject);
if (isset($subjs[1])) {
    $subject = $subjs[1] . '<br />' . $subjs[0];
} else {
    $subject = $model->subject;
}
?>

<div class='pb5 js_chat_item_<?= $model->id ?>'>
    <div>
        <?php
        $name = $receiver_name . Html::tag('span', '', ['class' => $online_status . ' js_online_status_' . $receiver_id]);
        $name .= "<div class='false_link'></div>";
        echo Html::a(
            $name,
            ['chat/view', 'id' => $model->id],
            [
                'class' => 'chat_contact no_underline js_open_chat',
                'data-id' => $model->id,
                'data-subject' => $model->subject,
                'data-link' => '/' . $model->subject_link,
                'data-receiver' => $receiver_id,
                'data-key' => $receiver_key
            ]
        ); ?>

        <span class="pull-right font12">
            <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
        </span>
    </div>
    <div class="clearfix">
        <div class="font12 pull-right chat_status">
            <i></i>
            <span class="do_archive_chat glyphicon glyphicon-remove <?= $js_archive ?>" title="<?= $delete ?>"></span>
        </div>
        <div class="font12 color5 text-nowrap ellip"><?= $subject; ?></div>
    </div>
</div>