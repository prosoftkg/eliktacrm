<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = 'Добавить компанию';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-create">

    <h1 class="general_heading"><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
