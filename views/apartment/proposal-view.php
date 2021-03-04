<?
use app\models\Apartment;
use yii\helpers\Html;
use yii\helpers\Url;

$data = \Yii::$app->session->get('data');
$apartment = Apartment::findOne($data->apartment);
$company = $apartment->building->object->company;
?>
<div class="proposal-wrap">
    <div id="print_area">
        <div class="proposal-header">
            <?php
            $auth = Yii::$app->authManager;
            echo Html::img(Url::base() . "/images/company/" . $company->img, ['class' => 'proposal-company-logo']);
            echo "<div class='proposal_company_info'>";
            echo Html::tag('span', $company->name, ['class' => 'proposal-company-name']);
            echo Html::tag('div', "Адрес: " . $company->address, ['class' => 'proposal-company-contact']);
            echo Html::tag('div', "Контактный телефон: " . $company->phone, ['class' => 'proposal-company-contact']);
            if (!Yii::$app->user->isGuest && (Yii::$app->user->identity->isAdmin || $auth->getAssignment('owner', Yii::$app->user->id))):
                echo Html::tag('div', "Директор компании: " . Yii::$app->user->identity->profile->name, ['class' => 'proposal-company-contact']);
            elseif (!Yii::$app->user->isGuest && ($auth->getAssignment('manager', Yii::$app->user->id))):
                echo Html::tag('div', "Менеджер: " . Yii::$app->user->identity->profile->name, ['class' => 'proposal-company-contact']);
            endif;
            echo "</div>";
            ?>
        </div>

        <div class="clear"></div>
        <h1 class="minor_heading"><?= $apartment->building->object->title; ?></h1>


        <div class="final_date">
            Срок сдачи объекта: <?= $apartment->building->object->due_quarter ?>-й
            квартал <?= $apartment->building->object->due_year; ?>-го года
        </div>
        <div class="proposal-left-block">
            <?= Html::img(Url::base() . '/images/building/s-' . $apartment->building->img, ['class' => 'proposal-building-img']); ?>
        </div>

        <div class="proposal-right-block">
            <div class="outer">
                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'>Общая площадь: </span>
                    <span class='object_field'><?= $apartment->plan->area . " м2"; ?></span>
                </div>

                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'>Количество комнат: </span>
                    <span class='object_field'><?= $apartment->plan->room_count; ?></span>
                </div>

                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'>Цена: </span>
                    <span class='object_field'>$<?= $apartment->getPrice('dollar'); ?></span>
                </div>

                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'>Номер подъезда: </span>
                    <span class='object_field'><?= $apartment->entry_num; ?></span>
                </div>

                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'>Номер квартиры: </span>
                    <span class='object_field'><?= $apartment->number; ?></span>
                </div>

                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'>Этаж: </span>
                    <span class='object_field'><?= $data->floor; ?></span>
                </div>
            </div>

        </div>

        <div class="clear"></div>
        <h1 class="minor_heading" style="margin: 15px 0;">Планировка</h1>

        <div class="proposal-left-block">
            <?php
            $roomArr = unserialize($apartment->plan->rooms);
            foreach ($roomArr as $key => $val):?>
                <div class="outer_container">
                    <div class='filler'></div>
                    <span class='object_label'><?= $key; ?></span>
                    <span class='object_field'><?= $val; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="proposal-right-block">
            <?= Html::img(Url::base() . "/images/plan/" . $apartment->plan->id . "/s-" . $apartment->plan->img, ['class' => 'proposal-plan-img']); ?>
        </div>

        <?php
        $monthlyFee = round(($apartment->getPrice('dollar') - $data->prepay) / $data->period, 2);
        ?>
        <div class="clear"></div>
        <h1 class="minor_heading" style="margin: 15px 0;">Условия рассрочки</h1>

        <div class="proposal-final-price">Стоимость: $<?= $apartment->getPrice('dollar'); ?></div>
        <div class="proposal-final-price">Первоначальный взнос: $<?= $data->prepay; ?></div>
        <div class="proposal-final-price">Срок расскрочки: <?= $data->period; ?> месяцев</div>
        <div class="proposal-final-price">Сумма ежемесячных выплат: $<?= $monthlyFee; ?></div>

    </div>
    <? echo Html::tag('span', 'Распечатать', ["class" => "btn button-print"]); ?>
</div>

<?
$script = <<<SCRIPT

$('.button-print').click(function(){
    w=window.open(null, 'Print_Page', 'scrollbars=yes');
        var styles = '<link rel="stylesheet" href="../css/site.css" />';
        var font = '<link href="https://fonts.googleapis.com/css?family=Exo+2:400,600,700&amp;subset=cyrillic" rel="stylesheet">'
        w.document.write(styles + font + jQuery('#print_area').html());
        w.document.close();
        w.print();
});
SCRIPT;
$this->registerJs($script);

Yii::$app->session->remove('data');
?>

