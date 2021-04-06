<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'My messages');
if (Yii::$app->request->get('archive')) {
    $inbox_active = '';
    $archive_active = 'active';
    $inArchive = Yii::t('app', 'in archive');
    $delete = Yii::t('app', 'Delete');
    $js_archive = 'js_delete_chat';
} else {
    $inbox_active = 'active';
    $archive_active = '';
    $inArchive = '';
    $delete = Yii::t('app', 'To archive');
    $js_archive = 'js_archive_chat';
}
?>
<!--<div class="js_chat_id" style="width: 100px; height: 100px; overflow: scroll;"><div style="height: 200px; width: 100px;"></div> </div>-->
<?php
Pjax::begin();
?>
<div class="row">
    <div class="mb15 menu2 pl15 font13">
        <span class="ctg_back js_chat_back js_link link hiddeniraak"><i class="glyphicon glyphicon-menu-left"></i> <?= Yii::t('app', 'Back') ?></span>
        <?php
        echo Html::a(Yii::t('app', 'Messages'), ['index'], ['class' => 'mr15 ' . $inbox_active]);
        echo Html::a(Yii::t('app', 'Archive'), ['index', 'archive' => 1], ['class' => 'mr15 ' . $archive_active]);
        ?>
    </div>
    <?php
    if ($dataProvider->getTotalCount()) {
    ?>
        <div class="text-center hiddeniraak js_filter_loading">
            <span class="glyphicon glyphicon-hourglass glyphicon-spin glyphicon-2x yellow"></span>
        </div>
        <div class="my-adds my-msg col-sm-5 pr0 mob_pr15 js_chat_container">
            <?php
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['class' => 'chat-item rel hover_bg pad10 js_show_child_hover', 'data-view' => 'apartment'],
                'summary' => '',
                'layout' => "{items}\n<div class='pager-wrap'>{pager}</div>",
                'itemView' => function ($model) use ($delete, $js_archive, $inbox_active) {
                    if ($inbox_active && !$model->archive || !$inbox_active && $model->archive) {
                        return $this->render('_chat_item', ['model' => $model, 'delete' => $delete, 'js_archive' => $js_archive]);
                    }
                },
                //'pager' => ['class' => \kop\y2sp\ScrollPager::className(),'item'=>'.chat-item','triggerOffset'=>4]
            ])
            ?>
        </div>
        <div class="col-sm-7 pl0 mob_pl15 only_desk js_board_container">
            <div class="box chatbox js_chatbox_wrap">
                <div class="abs chat_loading hiddeniraak js_chat_loading"><span class="glyphicon glyphicon-hourglass glyphicon-spin glyphicon-2x yellow"></span></div>
                <div class="text-center js_chat_bg"><span class="glyphicon glyphicon-envelope chat_bg"></span></div>
            </div>

            <div class="chat_input_wrap bg-fa box pad15 hiddeniraak">
                <textarea class="box w100 js_chat_input chat_input font12 pad5" data-key="" placeholder=<?= Yii::t('app', 'Reply..') ?> maxlength="500"></textarea>
                <div class="text-right mt4">
                    <span class="filter_checkbox_wrap mr15 only_desk">
                        <span class="check_square abs"></span>
                        <i class="check_tick abs"></i>
                        <label>
                            <input type="checkbox" value="1" name="enter" class="js_send_enter" checked="checked">
                            Enter — <?= Yii::t('app', 'Send') ?>
                        </label>
                    </span>
                    <button class="btn btn-primary btn-sm js_chat_send chat_send">
                        <i class="glyphicon glyphicon-send white"></i>
                        <span class="only_desk"><?= Yii::t('app', 'Reply') ?></span>
                    </button>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo "<div class='text-center'>" . Yii::t('app', 'No messages yet') . ' ' . $inArchive . "</div>";
    }
    ?>
</div>
<?php
Pjax::end();
?>