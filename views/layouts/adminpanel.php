<?php

use yii\helpers\Html;
?>
<section>
    <div class="admin-panel js_panel">
        <span class='adminicon glyphicon glyphicon-option-vertical js_panel_toggle' style='padding-left:13px;'></span>
        <ul>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>💼</span>Компании",
                    ['/company'],
                    ['title' => 'Компании', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>🏬</span>Объекты",
                    ['/object'],
                    ['title' => 'Объекты', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>🏗️</span>Строения",
                    ['/building'],
                    ['title' => 'Строения', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>⛩️</span>Подъезды",
                    ['/entry'],
                    ['title' => 'Подъезды', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>🔑</span>Квартиры",
                    ['/apartment'],
                    ['title' => 'Квартиры', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>📐</span>Планировки",
                    ['/plan'],
                    ['title' => 'Планировки', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>👨‍👩‍👧‍👦</span>Клиенты",
                    ['/client'],
                    ['title' => 'Клиенты', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>🤝</span>Сделки",
                    ['/deal'],
                    ['title' => 'Сделки', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>💵</span>Оплаты",
                    ['/payment'],
                    ['title' => 'Оплаты', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'>👤</span>Пользователи",
                    ['/user/admin'],
                    ['title' => 'Пользователи', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
            <li>
                <?= Html::a(
                    "<span class='adminicon'></span>",
                    ['/'],
                    ['title' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'left']
                ); ?>
            </li>
        </ul>
    </div>
</section>