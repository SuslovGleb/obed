<?php

use yii\helpers\Html;
use backend\modules\clientorder\assets\ClientorderAsset;

echo Html::csrfMetaTags();
ClientorderAsset::register($this);

//$orderTables=\backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrderInfo);
//print_r($test);
if(!$showOnlyTable) {
?>
<div class=" print printContain">
    <a href="#" onclick="window.print();" class="btn btn-primary printDishorder">Распечатать
        <div style="margin-left:20px" class="glyphicon glyphicon-print"></div>
    </a>


    <div class="btn btn-danger markAsPrinted">Отметить как распечатаный</div>
    <div class=" print printContain">
        <div class="flyerDay">
            <div class="flyerRow row1" style="text-transform: none">
                <h2>УВАЖАЕМЫЙ КЛИЕНТ!</h2>
                <p>Доставка комплексных обедов по городу принимается при заказе от 120 рублей, стоимость доставки 50 рублей.</p>
                <p>При заказе от 230 рублей доставка бесплатная.</p>
                <p>В стоимость комплексных обедов входит индивидуальная упаковка, приборы и хлеб.</p>
                <p>У нас Вы можете заказать  любые блюда из предлагаемого меню на текущий день .</p>
                <p>Любые вопросы Вы можете уточнить по телефону</p>
                <h3><span class="glyphicon glyphicon-earphone" style="font-size: 16px"></span> 8-904-024-61-72</h3>

            </div>
            <div class="flyerRow row2">
                <h2>ОБРАТИТЕ ВНИМАНИЕ</h2>
                <p>Все повара имеют огромный опыт, готовя по ГОСТ стандартам СССР</p>
                <p>Поэтому все обеды готовятся только из натуральных продуктов без различного рода присыпок и добавок</p>
                <p>Мы делаем все, чтобы обеды были не только вкусными, но и полезными</p>
                <p>Мы очень хотим чтобы Вам понравилась наша кухня.</p>
                <p>Просим к столу! Кушать подано!</p>

            </div>
            <div class="flyerRow row3">
                <h2>"КУШАТЬ ПОДАНО"</h2>
                <h3>Доставка обедов</h3>
                <img src="/images/logo.svg" width="100%" alt="" style="-webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5));
                        filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5));
                        ">            <h4>ЗАКАЗ ОБЕДОВ&nbsp;НА ТЕКУЩИЙ ДЕНЬ  <br>      С 9-00 до 11-00</h4>
                <h4>ЗАКАЗ ОБЕДОВ НА СЛЕДУЮЩИЙ ДЕНЬ <br> С 11-00 до 14-00</h4>
                <br>
                <br>
                <h3><span class="glyphicon glyphicon-earphone" style="font-size: 16px"></span> 8-904-024-61-72</h3>
                <h3><span class="glyphicon glyphicon-globe" style="font-size: 16px"></span> new.tverobedi.ru</h3>

            </div>

        </div>
       
    </div>
    <?php
    }

   // echo $orderTables['tables'];


    ?>
</div>
