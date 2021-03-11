<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="stylesheet" href="<?= Url::base() . '/css/intro.css'; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Exo+2:400,600,700&amp;subset=cyrillic" rel="stylesheet">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="intro-one register-link">
        <div class="intro-one-overlay" style="padding-bottom: 0">
            <div class="intro-logo">ELITKA-CRM</div>
            <div style="margin-top: 25px" class="container">
                <?= $content ?>
            </div>
        </div>
    </div>

    <div class="intro-footer" style="margin-top: 0px;background-size: auto;">
        <div class="intro-footer-overlay" style="overflow: auto;padding-bottom: 0">
            <div class="intro-footer-left">
                АДРЕС: Кыргызская Республика, г. Бишкек, ул. Ленина 145</br>
                E-mail: info@elitka.kg
            </div>

            <div class="intro-footer-right">
                Copyright 2017 © ELITKA-CRM</br>
                Все права защищены
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
