<?php

/**
 * Created by PhpStorm.
 * User: Damir
 * Date: 9/30/16
 * Time: 3:08 AM
 */

use yii\widgets\ListView;

$this->title = Yii::t('app', 'Объекты компании');
$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = [
    'label' => 'Добавить объект',
    'url' => ['object/create'],
    'template' => "<li class='bread-right-link'>{link}</li>\n"
];

?>
<div class="news-index">
    <div class="index-news">
        <!--<h1 class="general_heading">
            <?/*= Yii::t("app", $this->title); */?>
        </h1>-->
        <?=
        ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_own',
            'summary' => '',
            'itemOptions' => [
                'class' => 'object-block',
            ],
        ]); ?>

    </div>
    <div class="object_map">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2923.9429829832116!2d74.61112491439052!3d42.87404757915567!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x389eb7c6fb55b4c3%3A0x519c6ea8206ecb5e!2z0JrQuNC10LLRgdC60LDRjywgNTgsIDU4IEtpZXYgU3QsIEJpc2hrZWs!5e0!3m2!1sen!2skg!4v1479361324219" width="100%" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div>
</div>