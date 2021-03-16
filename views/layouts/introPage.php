<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="<?= Url::base() . '/css/intro.css?v=1'; ?>">
    <link rel="stylesheet" href="<?= Url::base() ?>/css/swiper.min.css">
    <link href="https://fonts.googleapis.com/css?family=Exo+2:100,100i,200,200i,300,400,600,700&amp;subset=cyrillic" rel="stylesheet">
    <meta charset="UTF-8" />
    <?= Html::csrfMetaTags() ?>
    <title>Элитка CRM - Удобная база объектов всегда под рукой</title>
    <?php $this->head() ?>
</head>

<body>

    <?php $this->beginBody() ?>
    <div class="welcome_page">
        <div class="intro_header">
            <div class="intro_menu">

            </div>
        </div>
        <div class="overlay">
            <div class="intro-one">
                <div class="intro-one-overlay">
                    <a href="user/login" class='intro_login'>Вход</a>
                    <div class="intro-logo">ELITKA-CRM</div>
                    <div class="intro-slogan">
                        Профессиональная система организации работы для компаний, которые строят и продают недвижимость
                        в Кыргызстане
                    </div>


                    <?php
                    Modal::begin([
                        'header' => '<h1 class="general_heading">Заказать презентацию</h1>',
                        'toggleButton' => [
                            'label' => 'Заказать презентацию',
                            'class' => 'order-button'
                        ],
                        'closeButton' => [
                            'label' => 'x',
                            'class' => 'btn btn-danger btn-sm pull-right',
                        ],
                    ]);
                    echo $this->render('presentation');
                    Modal::end();
                    ?>
                </div>
            </div>
            <div class="intro-two">
                <div class="intro-two-overlay">
                    <span class="intro-heading">
                        Удобная база объектов всегда под рукой
                    </span>

                    <p class="intro-adv">
                        Мы создали удобный каталог объектов с шахматкой,который поможет подобрать клиенту квартиру и
                        продемонстрировать её в лучшем виде.
                    </p>

                    <div class="advantage search">
                        <span class="search_bg"></span>
                        <span class="adv-sign">Параметрический поиск по квартирам</span>
                    </div>

                    <div class="advantage chess">
                        <span class="chess_bg"></span>
                        <span class="adv-sign">Каталог объектов с шахматкой</span>
                    </div>

                    <div class="advantage pricing">
                        <span class="pricing_bg"></span>
                        <span class="adv-sign">Гибкое управление ценами и объектами недвижимости</span>
                    </div>

                    <div class="advantage sync">
                        <span class="sync_bg"></span>
                        <span class="adv-sign">Синхронизация с сайтами и другими системами</span>
                    </div>
                </div>

            </div>
            <div class="intro-three">
                <div class="intro-three-overlay">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><?= Html::img(Url::base() . "/images/intro/slideOne.jpg"); ?></div>
                            <div class="swiper-slide"><?= Html::img(Url::base() . "/images/intro/slideTwo.jpg"); ?></div>
                        </div>
                    </div>

                    <!-- Swiper JS -->
                    <script src="<?= Url::base() ?>/js/swiper.min.js"></script>

                    <!-- Initialize Swiper -->
                    <script>
                        var swiper = new Swiper('.swiper-container', {
                            autoplay: 2500,
                            autoplayDisableOnInteraction: false,
                            loop: true
                        });
                    </script>
                </div>
            </div>
            <div class="intro-four">
                <div class="intro-four-overlay">
                    <div class="black-heading">
                        Мы сделали систему недорогой для всех. У нас гибкое ценообразование, которое зависит от количества объектов.
                    </div>
                </div>

                <div class="tariffication">
                    <div class="tariff-block">
                        <div class="tariff-header">Тариф "Старт"</div>
                        <div class="tariff-price">0 сом</div>
                        <div class="tariff-term">ЗА МЕСЯЦ <br>1 объект</div>
                        <div class="tariff-crew">1-3 сотрудников</div>

                        <?php
                        Modal::begin([
                            'header' => '<h1 class="general_heading">Заказать презентацию</h1>',
                            'toggleButton' => [
                                'label' => 'Заказать презентацию',
                                'class' => 'tariff-order'
                            ],
                            'closeButton' => [
                                'label' => 'x',
                                'class' => 'btn btn-danger btn-sm pull-right',
                            ],
                        ]);
                        echo $this->render('presentation');
                        Modal::end();
                        ?>
                    </div>

                    <div class="tariff-block">
                        <div class="tariff-header">Тариф "Базовый"</div>
                        <div class="tariff-price">0 сом</div>
                        <div class="tariff-term">ЗА МЕСЯЦ <br>3 объекта</div>
                        <div class="tariff-crew">1-5 сотрудников</div>
                        <?php
                        Modal::begin([
                            'header' => '<h1 class="general_heading">Заказать презентацию</h1>',
                            'toggleButton' => [
                                'label' => 'Заказать презентацию',
                                'class' => 'tariff-order'
                            ],
                            'closeButton' => [
                                'label' => 'x',
                                'class' => 'btn btn-danger btn-sm pull-right',
                            ],
                        ]);
                        echo $this->render('presentation');
                        Modal::end();
                        ?>
                    </div>

                    <div class="tariff-block tariff-last">
                        <div class="tariff-header">Тариф "Премиум"</div>
                        <div class="tariff-price">0 сом</div>
                        <div class="tariff-term">ЗА МЕСЯЦ <br>до 10 объектов</div>
                        <div class="tariff-crew">до 10 сотрудников</div>
                        <?php
                        Modal::begin([
                            'header' => '<h1 class="general_header">Заказать презентацию</h1>',
                            'toggleButton' => [
                                'label' => 'Заказать презентацию',
                                'class' => 'tariff-order'
                            ],
                            'closeButton' => [
                                'label' => 'x',
                                'class' => 'btn btn-danger btn-sm pull-right',
                            ],
                        ]);
                        echo $this->render('presentation');
                        Modal::end();
                        ?>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="intro-five">
                <div class="intro-five-overlay">
                    <p style="text-align: center; line-height: 34px; font-size: 24px;margin-top: 25px;">
                        Остались вопросы?
                    </p>

                    <p style="text-align: center; line-height: 34px; font-size: 24px;">
                        Мы с удовольствием проведем презентацию системы у вас в офисе или по Skype.<br>
                        В ходе презентации, наш специалист ответит на любые ваши вопросы.
                    </p>

                    <? //= Html::a('Заказать презентацию', '#call_back'); ?>
                    <?php
                    Modal::begin([
                        'header' => '<h1 class="general_heading">Заказать презентацию</h1>',
                        'toggleButton' => [
                            'label' => 'Заказать презентацию',
                            'class' => 'blue-order-presentation'
                        ],
                        'closeButton' => [
                            'label' => 'x',
                            'class' => 'btn btn-danger btn-sm pull-right',
                        ],
                    ]);
                    echo $this->render('presentation');
                    Modal::end();
                    ?>
                </div>
            </div>
        </div>
        <div class="intro-footer">
            <div class="intro-footer-overlay">
                <div class="intro-footer-left">
                    АДРЕС: Кыргызская Республика, г.Бишкек. ул.Ахунбаева 119а</br>
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