<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Apartment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Apartments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
echo $model->object->company->name . ';' . $model->plan->room_count . ' - ком. кв. ЖК ' . $model->object->title;
?>
<div class="apartment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php echo Html::tag('a', Yii::t('app', 'Message seller'), [
        'class' => 'btn btn-primary btn-lgg mt10 js_chat_modal',
        //'data-receiver' => $model->object->company->owner_id
    ]); ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'entry_id',
            'entry_num',
            'status',
            'number',
            'building_id',
        ],
    ]);




    ?>

</div>
<?php include_once(Yii::getAlias('@app') . '/views/chat/_chat_modal.php'); ?>