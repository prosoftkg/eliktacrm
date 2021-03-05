<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Exo+2:400,600,700&amp;subset=cyrillic" rel="stylesheet">
    <script type="text/javascript">
        function showContent(link) {
            var cont = document.getElementById('load-content');
            var loading = document.getElementById('loading');
            cont.innerHTML = loading.innerHTML;
            var http = createRequestObject();
            if (http) {
                http.open('get', link);
                http.onreadystatechange = function() {
                    if (http.readyState == 4) {
                        cont.innerHTML = http.responseText;
                    }
                }
                http.send(null);
            } else {
                document.location = link;
            }
        }

        function createRequestObject() {
            try {
                return new XMLHttpRequest()
            } catch (e) {
                try {
                    return new ActiveXObject('Msxml2.XMLHTTP')
                } catch (e) {
                    try {
                        return new ActiveXObject('Microsoft.XMLHTTP')
                    } catch (e) {
                        return null;
                    }
                }
            }
        }
    </script>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <div class="nav-cover">
            <?php
            NavBar::begin([
                'brandLabel' => 'Elitka CRM',
                'brandUrl' => Url::base() . '/user/' . Yii::$app->user->id,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $username = "";
            if (!Yii::$app->user->isGuest) {
                $username = [
                    'label' => Yii::$app->user->identity->username, 'items' => [
                        ['label' => 'Личный кабинет', 'url' => ['/user/profile', 'id' => Yii::$app->user->id], ['class' => 'btn btn-link']],
                        ['label' => 'Выйти', 'url' => '/user/logout'],
                    ], ['style' => 'color:#fff']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right nav-exo'],
                'items' => [
                    //['label' => 'Компания', 'url' => ['/company/view','id'=>Yii::$app->user->identity->company_id]],
                    ['label' => 'Сделки', 'url' => ['/deal/index']],
                    ['label' => 'Задачи', 'url' => ['/assignment/index']],
                    ['label' => 'Объекты', 'url' => ['/object/own']],
                    ['label' => 'Планировки', 'url' => ['/plan/own']],
                    ['label' => 'Поиск', 'url' => ['/apartment/selector']],
                    ['label' => 'Запросы', 'url' => ['/request/index']],
                    ['label' => 'Менеджеры', 'url' => ['/user/admin/manager-list']],
                    ['label' => 'Каналы продаж', 'url' => ['/reference/index']],
                    $username,
                    /*Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/user/login']]
                ) : (
                    '<li>'
                        . Html::beginForm(['/site/logout'], 'post', ['class' => 'navbar-form'])
                        . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->username . ')',
                        ['class' => 'btn btn-link']
                    )
                        . Html::endForm()
                        . '</li>'
                )*/
                ],
            ]);
            NavBar::end();
            ?>
        </div>

        <div class="bread_bg">
            <div class="bread_aligner">
                <div class="bread_central">
                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => 'Главная',
                            'url' => Url::base() . '/user/' . Yii::$app->user->id
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="container">
            <?
        //phpinfo();
        ?>
            <?= $content ?>
        </div>
    </div>
    <?php
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin) {
        include_once('adminpanel.php');
    }
    ?>
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Элитка CRM </p>

            <p>Ограничение отвественности</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>