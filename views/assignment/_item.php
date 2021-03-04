<div class="assignment-block">
    <div class="assignment-padder">
        <div class="assignment-date">
            <? $formatter = \Yii::$app->formatter; ?>
            <?php echo $formatter->asDate($model->date_to, 'long'); ?>
        </div>

        <div class="assignment_right">
            <div class="assignment-title">
                <?= $model->assignmentType($model->type); ?>
            </div>

            <div class="assignment-description">
                <?= $model->description; ?>
            </div>
        </div>
       <?php if(Yii::$app->user->id):?> <div class="assignment-remove-button" data-id="<?=$model->id;?>"></div><?php endif;?>
    </div>
</div>