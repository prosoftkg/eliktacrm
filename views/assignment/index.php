<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AssignmentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignment-index">

    <h1 class="general_heading" style="margin-bottom: 10px">Открытые задачи</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?php
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'summary' => false,
        'options' => [],
    ]); ?>
    <?php /*= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'type',
            'priority',
            'status',
            'date_from',
            // 'date_to',
            // 'description',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */
    ?>
</div>

<?php $script = <<< JS
$('.assignment-remove-button').on('click', function(e) {
    var id = $(this).attr('data-id');
    var r = confirm("Вы уверены что хотите удалить задачу?");
    var thisOne = $(this);
    if(r==true)
    {
        $.ajax({
               method:"POST",
               url: '/assignment/remove',
               data: {id: id},
               success: function(data) {
                    thisOne.parents('.assignment-block').fadeOut(500);
               }
            });
    }
    else
    {

    }

});
JS;
$this->registerJs($script); ?>