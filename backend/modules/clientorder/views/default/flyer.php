<?php

use backend\modules\clientorder\assets\ClientorderAsset;
use backend\controllers\Svg;
use yii\helpers\Html;

$bundle=ClientorderAsset::register($this);

?>
<style>

    .flyerDay {
        /*padding: 10px;*/
    }
    .flyerDay::after {
        content: "";
        background: rgba(0, 0, 0, 0) url(/admin/assets/b6034794/images/free-lunch-vector.jpg) no-repeat!important;
        opacity: 0.3;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        position: fixed;
        z-index: -1;
        background-size: 100%!important;
    }

</style>

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
            <?= Html::img('/images/logo.svg',
                $options = [
                    'width' => '100%',
                    'style' => '-webkit-filter: drop-shadow(11px 9px 3px rgba(0, 39, 7, 0.5));
                        filter: drop-shadow(11px 9px 6px rgba(0, 39, 7, 0.5));
                        '
                ]); ?>
            <h4>ЗАКАЗ ОБЕДОВ НА ТЕКУЩИЙ ДЕНЬ  <br>      С 9-00 до 11-00</h4>
            <h4>ЗАКАЗ ОБЕДОВ НА СЛЕДУЮЩИЙ ДЕНЬ <br> С 11-00 до 14-00</h4>
            <br>
            <br>
            <h3><span class="glyphicon glyphicon-earphone" style="font-size: 16px"></span> 8-904-024-61-72</h3>
            <h3><span class="glyphicon glyphicon-globe" style="font-size: 16px"></span> new.tverobedi.ru</h3>

        </div>

    </div>
<?

$svg=new Svg;
$color[1]='#9C4403';
$color[2]='#BD5203';
$color[3]='#C75703';
$color[4]='#F26A04';
$color[5]='#F26A04';
$small_images['1']=$svg->Salat($color[1],20*2);
$small_images['2']=$svg->Sup($color[2],16*2);
$small_images['3']=$svg->Meet($color[3],15*2);
$small_images['4']=$svg->Garnir($color[4],16*2);
$small_images['5']=$svg->Vipechka($color[5],15*2);

$garnir=[];
$i=0;
$dayN=0;
$productTable=[];
foreach ($allDishesbyDay as $day => $Dishes) {
    $types = [];
    foreach ($Dishes as $key => $dish) {
        $types[$i][$dish['dishType']['type']]['type_id'] = $dish['dishType']['id'];
        $types[$i][$dish['dishType']['type']][$dish['id']]['name'] = $dish['name'];
        $types[$i][$dish['dishType']['type']][$dish['id']]['cost'] =
        round($dish['dishCost']['cost']*1.25/5) * 5;
        //$dish['dishCost']['cost'];
        $types[$i][$dish['dishType']['type']][$dish['id']]['weight'] = $dish['dishCost']['weight'];
        foreach ($dish['dishProducts'] as $key => $product) {
            if ($product['product']['productSynonyms']['client_synonym'])
            {
                $types[$i][$dish['dishType']['type']][$dish['id']]['product'][] = $product['product']['productSynonyms']['client_synonym'];
                $productTable[$product['product']['productSynonyms']['client_synonym']]=$product['product']['productSynonyms']['client_synonym'];
            }

        }
//

    }
//                echo '</pre>';echo '<pre>';
//
////print_r($types);
//                echo '</pre>';
    $dayN++;

    if($dayN==4)
    {
        $dayN=1;
        echo '</div>';
        echo '<div class="flyerDay">';
        echo '<div class="flyerRow row1">';
    }
    elseif($dayN==1)
    {
        echo '<div class="flyerDay">';
        echo '<div class="flyerRow row1">';
    }
    else
    {
        echo '<div class="flyerRow row'.$dayN.'">';
    }
$days = array( 1 => 'Понедельник' , 'Вторник' , 'Среду' , 'Четверг' , 'Пятницу' , 'Субботу' , 'Воскресенье' );


$today=date($days[date("N", strtotime($day))]);
$tomorow=date($days[date("N", strtotime('tomorrow'))]);

echo '<div class="complex-name colour-1" style="    float: unset;    text-align: center;    width: 100%;">Меню на '.$today.'</div>';
    foreach ($types as $dayNum => $type) {
                $complexDisesId = [];
                foreach ($type as $typeName => $Dish) {

                    if ($Dish['type_id'] == 4)
                    {
                        $garnir[]=$Dish;
                    }

                    if (
                        $Dish['type_id'] == 1
                        || $Dish['type_id'] == 2
                        || $Dish['type_id'] == 3
                        || $Dish['type_id'] == 4
                    ) {
                        echo '<div class="type">';


                        $N = 0;

                        foreach ($Dish as $dishKey => $dishName) {
                            $mach=0;
                            foreach ($everyDayDish1 as $key=>$dval)
                            {
                                if($dishKey==$dval->id)
                                {
                                    $mach++;
                                }
                            }

                            if ($dishKey != 'type_id') {
                                if (!$mach) {

                                    if($N==0)
                                    {
                                        echo '<div class="typeName">'. $typeName . '</div>';
                                    }
                                    $N++;
                                    $complexDisesId[$dishKey]['N'] = $N;
                                    $complexDisesId[$dishKey]['Name'] = $dishName['name'];
                                    echo '<div class="dishBox id' . $dishKey . '">';
                                    echo '<div class="dish">';
//                                            echo '<div class="dishNum">№' . $N . ':</div>';
                                    echo '<div class="dishName " >' . $dishName['name'] . '</div>';
                                    echo '<div class="dishCost" >
                                                        <span style="    margin-right: 3px;">' . $dishName['cost'] . 'р</span>
                                                        /
                                                        <span style="float: right;">' . $dishName['weight'] . 'г</span>
                                                    </div>';

                                    if (count($dishName['product'])) {
                                        $dishProduct = '(';
                                        $i = 0;
                                        foreach ($dishName['product'] as $key => $productName) {
                                            $i++;
                                            $dishProduct .= $productName;
                                            if ($i != count($dishName['product'])) {
                                                $dishProduct .= ',';
                                            }
                                        }
                                        $dishProduct .= ')';
                                    }
                                    $products[$typeName][$dishName['name']] = $dishProduct;
                                    echo '<div class="products">';
                                    if (count($dishName['product'])) {
                                        echo '(';
                                        $i = 0;
                                        foreach ($dishName['product'] as $key => $productName) {
                                            $i++;


                                            echo $productName;
                                            if ($i != count($dishName['product']))
                                                echo ',';


                                        }
                                        echo ')';
                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                } else {
                                    $everyDayDish[$typeName][$dishKey] = $dishName;
                                }
                            }

                        }
                        echo '</div>';
                    }
                    //остальные блюда на каждый день
//                    else
//                    {
//                        $everyDayDish[$typeName][$dishKey] = $dishName;
//                    }

                }

                ?>
            </div>

        <?php
    }
}
        echo '<div class="flyerRow row3">';
        echo '<div class="complex-name colour-1" style="    float: unset;    text-align: center;    width: 100%;">Каждый день</div>';
//выпечка
//            echo '<div class="typeName">'. $typeName . '</div>';
//            $N = 0;

//            foreach ($Dish as $dishKey => $dishName) {
//                if ($dishKey != 'type_id') {
//
//                    $N++;
//                    $complexDisesId[$dishKey]['N'] = $N;
//                    $complexDisesId[$dishKey]['Name'] = $dishName['name'];
//                    echo '<div class="dishBox">';
//                    echo '<div class="dish">';
//                    //<div class="dishNum">№' . $N .
//                    //  </div>
//                    echo ' <div class="dishName" style="color:black!important">' . $dishName['name'] . '</div>';
//                    echo '<div class="dishCost" >
//                                                        <span style="    margin-right: 3px;">' . $dishName['cost'] . 'р</span>
//                                                        /
//                                                        <span style="float: right;">' . $dishName['weight'] . 'г</span>
//                                                    </div>';
//                                    echo '<div class="products">';
//                                    if (count($dishName['product'])) {
//                                        echo '(';
//                                        $i=0;
//                                        foreach ($dishName['product'] as $key => $productName) {
//                                            $i++;
//
//
//                                            echo $productName;
//                                            if($i!=count( $dishName['product']))
//                                            echo ',';
//
//
//                                        }
//                                        echo ')';
//                                    }
//                                    echo '</div>';
//                    echo '</div>';
//                    echo '</div>';
//                }
//            }

        foreach ($everyDayDish as $type=>$everyDish)
        {
            echo '<div class="typeName">'.$type.'</div>';
            foreach ($everyDish as $dishKey => $dishName) {
                if ($dishKey != 'type_id') {

                    echo '<div class="dishBox">';
                    echo '<div class="dish">';
                    //<div class="dishNum">№' . $N .
                    //  </div>
                    echo ' <div class="dishName">' . $dishName['name'] . '</div>';
                    echo '<div class="dishCost">
                                                        <span style="    margin-right: 3px;">' . $dishName['cost'] . 'р</span>
                                                        /
                                                        <span style="float: right;">' . $dishName['weight'] . 'г</span>
                                                    </div>';
                    echo '<div class="products">';
                    if (count($dishName['product'])) {
                        echo '(';
                        $i=0;
                        foreach ($dishName['product'] as $key => $productName) {
                            $i++;


                            echo $productName;
                            if($i!=count( $dishName['product']))
                                echo ',';


                        }
                        echo ')';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                }
            }

        }

        echo '</div>';
?>
</div>


            <div class="flyerDay">
                <div class="dayName"></div>
                <div class="flyerRow row1">
                    <?php
                    $i=0;
                        foreach ($complexes as $complex) {

                            if (
                                stristr($complex->name, 'ланч') == true
                                || stristr($complex->name, 'Ланч') == true
                            ) {
                                $i++;
                                echo '<div class="complex-name colour-'.$i.'">'.$complex->name.'</div>';
                                echo '<div class="complex-price colour-'.$i.'">'.round($complex->price*1.25,-1).'</div>';
                            //.$complex->price.'</div>';
                                echo '<br>';
                                foreach ($complex->complexMenus as $complexMenu) {
                                    //echo '<pre>';
                                    echo '<div class="complex-type-box">';
                                    echo '<div class="complex-type">'.$complexMenu->type->type.'</div>';
                                    //echo $complexMenu->type->id;
                                    echo '<div class="complex-type-comment">';
                                    //echo '</pre>';
                                    if (
                                        stristr($complex->name, 'эконом') == true
                                        || stristr($complex->name, 'Эконом') == true
                                    ) {
                                        if ($complexMenu->type->id == 1) {
                                            echo '(только овощные)';
                                        }
                                        if ($complexMenu->type->id == 2) {
                                            echo '(любые)';
                                        }
                                        if ($complexMenu->type->id == 3) {
                                            echo '(только котлета)';
                                        }
                                        if ($complexMenu->type->id == 4) {
                                            echo '(рис/гречка/макароны)';
                                        }

                                    }
                                    if (
                                        stristr($complex->name, 'стандарт') == true
                                        || stristr($complex->name, 'Стандарт') == true
                                    ) {
                                        if ($complexMenu->type->id == 1) {
                                            echo '(любые)';
                                        }
                                        if ($complexMenu->type->id == 2) {
                                            echo '(любые)';
                                        }
                                        if ($complexMenu->type->id == 3) {
                                            echo '(блюда из курицы и котлета)';
                                        }
                                        if ($complexMenu->type->id == 4) {
                                            echo '(любые)';
                                        }

                                    }
                                    if (
                                        stristr($complex->name, 'бизнес') == true
                                        || stristr($complex->name, 'Бизнес') == true
                                    ) {
                                        if ($complexMenu->type->id == 1) {
                                            echo '(любые)';
                                        }
                                        if ($complexMenu->type->id == 2) {
                                            echo '(любые)';
                                        }
                                        if ($complexMenu->type->id == 3) {
                                            echo '(блюда из свинины и рыбы)';
                                        }
                                        if ($complexMenu->type->id == 4) {
                                            echo '(любые)';
                                        }

                                    }
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<br>';
                                }

                                echo '<br>';

                            }

                        }
                    ?>
                </div>
                <div class="flyerRow row2">
                    <?php
                    $i=0;
                    foreach ($complexes as $complex) {


                        if (
                            stristr($complex->name, 'ланч') == true
                            || stristr($complex->name, 'Ланч') == true
                        ) {}
                        else
                        {
                            $i++;
                            echo '<div class="complex-name colour-'.$i.'">'.$complex->name.'</div>';
                            echo '<div class="complex-price colour-'.$i.'">'
                            .round($complex->price*1.25,-1).'</div>';
                            //.$complex->price.'</div>';
                            echo '<br>';
                            foreach ($complex->complexMenus as $complexMenu) {
                                //echo '<pre>';
                                echo '<div class="complex-type-box">';
                                echo '<div class="complex-type">'.$complexMenu->type->type.'</div>';
                                //echo $complexMenu->type->id;
                                echo '<div class="complex-type-comment">';
                                //echo '</pre>';
                                if (
                                    stristr($complex->name, 'эконом') == true
                                    || stristr($complex->name, 'Эконом') == true
                                ) {
                                    if ($complexMenu->type->id == 1) {
                                        echo '(только овощные)';
                                    }
                                    if ($complexMenu->type->id == 2) {
                                        echo '(любые)';
                                    }
                                    if ($complexMenu->type->id == 3) {
                                        echo '(только котлета)';
                                    }
                                    if ($complexMenu->type->id == 4) {
                                        echo '(рис/гречка/макароны)';
                                    }

                                }
                                if (
                                    stristr($complex->name, 'стандарт') == true
                                    || stristr($complex->name, 'Стандарт') == true
                                ) {
                                    if ($complexMenu->type->id == 1) {
                                        echo '(любые)';
                                    }
                                    if ($complexMenu->type->id == 2) {
                                        echo '(любые)';
                                    }
                                    if ($complexMenu->type->id == 3) {
                                        echo '(блюда из курицы и котлета)';
                                    }
                                    if ($complexMenu->type->id == 4) {
                                        echo '(любые)';
                                    }

                                }
                                if (
                                    stristr($complex->name, 'бизнес') == true
                                    || stristr($complex->name, 'Бизнес') == true
                                ) {
                                    if ($complexMenu->type->id == 1) {
                                        echo '(любые)';
                                    }
                                    if ($complexMenu->type->id == 2) {
                                        echo '(любые)';
                                    }
                                    if ($complexMenu->type->id == 3) {
                                        echo '(блюда из свинины и рыбы)';
                                    }
                                    if ($complexMenu->type->id == 4) {
                                        echo '(любые)';
                                    }

                                }
                                echo '</div>';
                                echo '</div>';
                                echo '<br>';
                            }

                            echo '<br>';

                        }

                    }

                    foreach ($types as $dayNum => $type) {
                        foreach ($type as $typeName => $Dish) {

                            if ($Dish['type_id'] == 5) {
                                $bakery='';

                                $i++;
                                $bakery= '<div class="typeSvg" style="margin-left: 0;">'. $small_images[$Dish['type_id']] . '</div>';
                                $bakery.= '<div class="typeName" style="color:'.$color[$Dish['type_id']].'!important; font-size: 12pt;float: unset;width: 100%;     line-height: 37px;">'. $typeName . '</div>';
                                $N = 0;

                                foreach ($Dish as $dishKey => $dishName) {
                                    if ($dishKey != 'type_id') {

                                        $N++;
                                        $complexDisesId[$dishKey]['N'] = $N;
                                        $complexDisesId[$dishKey]['Name'] = $dishName['name'];
                                        $bakery.= '<div class="dishBox" >';
                                        $bakery.= '<div class="dish">';
                                        //<div class="dishNum">№' . $N .
                                        //  </div>
                                        $bakery.= ' <div class="dishName" style="text-shadow: none!important">' . $dishName['name'] . '</div>';
                                        $bakery.= '<div class="dishCost" >
                                                        <span style="    margin-right: 3px;">' . $dishName['cost'] . 'р</span>
                                                        /
                                                        <span style="float: right;">' . $dishName['weight'] . 'г</span>
                                                    </div>';
//                                    echo '<div class="products">';
//                                    if (count($dishName['product'])) {
//                                        echo '(';
//                                        $i=0;
//                                        foreach ($dishName['product'] as $key => $productName) {
//                                            $i++;
//
//
//                                            echo $productName;
//                                            if($i!=count( $dishName['product']))
//                                            echo ',';
//
//
//                                        }
//                                        echo ')';
//                                    }
//                                    echo '</div>';
                                        $bakery.= '</div>';
                                        $bakery.= '</div>';
                                    }
                                }
                            }

                        }
                    }
                    echo $bakery;
                    ?>
                </div>

                <div class="flyerRow row3">
                    <?php
                    echo '<div class="complex-name colour-1" style="    float: unset;    text-align: center;    width: 100%;">Информация</div>';
                    echo '<p>Обратите внимание на то, что при заказе комплексных обедов цена ниже, чем при выборе по отдельности</p>';
                    echo '<p>Чтобы экономить Ваши деньги старайтесь сформировать выбранные Вами блюда в предложенные Выше комплексы</p>';
                    echo '<p>Если у Вас возникли затруднения, наш оператор с удовольствием поможет Вам правильно сформировать заказ</p>';
                    echo '<p>Также, подробная информация по составу комплексов расположена на нашем сайте, на котором Вы можете сформировать Ваш заказ и отправить его нам, минуя сотовую связь</p>';
                    echo '            <br>
            <br>
            <h3><span class="glyphicon glyphicon-earphone" style="font-size: 16px"></span> 8-904-024-61-72</h3>
            <h3><span class="glyphicon glyphicon-globe" style="font-size: 16px"></span> new.tverobedi.ru</h3>';

//                    $days = array( 1 => 'Понедельник' , 'Вторник' , 'Среду' , 'Четверг' , 'Пятницу' , 'Субботу' , 'Воскресенье' );
//                    $tomorow=date($days[date("N", strtotime('tomorrow'))]);
//
//                    echo '<div class="complex-name colour-1" style="    float: unset;    text-align: center;    width: 100%;">Меню на '.$tomorow.'</div>';
//                    foreach ($allDishesbyDay as $day => $Dishes) {
//
//
//                        $types = [];
//
//                        $tomorow=date("D", strtotime('tomorrow'));
//                        if($day==$tomorow) {
//                            foreach ($Dishes as $key => $dish) {
//                                $types[$i][$dish['dishType']['type']]['type_id'] = $dish['dishType']['id'];
//                                $types[$i][$dish['dishType']['type']][$dish['id']]['name'] = $dish['name'];
//                                $types[$i][$dish['dishType']['type']][$dish['id']]['cost'] = $dish['dishCost']['cost'];
//                                $types[$i][$dish['dishType']['type']][$dish['id']]['weight'] = $dish['dishCost']['weight'];
//                                foreach ($dish['dishProducts'] as $key => $product) {
//                                    if ($product['product']['productSynonyms']['client_synonym']) {
//                                        $types[$i][$dish['dishType']['type']][$dish['id']]['product'][] = $product['product']['productSynonyms']['client_synonym'];
//                                    }
//
//                                }
////
//
//                            }
//                            foreach ($types as $dayNum => $type) {
//
//                                foreach ($type as $typeName => $Dish) {
//
//
//                                    if (
//                                        $Dish['type_id'] == 1
//                                        || $Dish['type_id'] == 2
//                                        || $Dish['type_id'] == 3
//                                        || $Dish['type_id'] == 4
//                                    ) {
//                                        echo '<div class="type">';
//                                        echo '<div class="typeSvg" style="margin-left: 0px; ">' . $small_images[$Dish['type_id']] . '</div>';
//                                        echo '<div class="typeName" style="color:'.$color[$Dish['type_id']].'!important; font-size: 12pt;float: unset;width: 100%; line-height: 37px;">' . $typeName . '</div>';
//                                        $N = 0;
//
//                                        foreach ($Dish as $dishKey => $dishName) {
//                                            if ($dishKey != 'type_id') {
//
//                                                $N++;
//                                                $complexDisesId[$dishKey]['N'] = $N;
//                                                $complexDisesId[$dishKey]['Name'] = $dishName['name'];
//                                                echo '<div class="dishBox" style="margin-left: 0px; ">';
//                                                echo '<div class="dish">
//                                            <div class="dishNum">№' . $N . ':</div>
//                                            <div class="dishName" style="text-shadow: none!important">' . $dishName['name'] . '</div>';
//                                                echo '<div class="dishCost" >
//                                                        <span style="    margin-right: 3px;">' . $dishName['cost'] . 'р</span>
//                                                        /
//                                                        <span style="float: right;">' . $dishName['weight'] . 'г</span>
//                                                    </div>';
//                                                if (count($dishName['product'])) {
//                                                    $dishProduct = '(';
//                                                    $i = 0;
//                                                    foreach ($dishName['product'] as $key => $productName) {
//                                                        $i++;
//                                                        $dishProduct .= $productName;
//                                                        if ($i != count($dishName['product'])) {
//                                                            $dishProduct .= ',';
//                                                        }
//                                                    }
//                                                    $dishProduct .= ')';
//                                                }
//                                                $products[$typeName][$dishName['name']] = $dishProduct;
////                                    echo '<div class="products">';
////                                    if (count($dishName['product'])) {
////                                        echo '(';
////                                        $i=0;
////                                        foreach ($dishName['product'] as $key => $productName) {
////                                            $i++;
////
////
////                                            echo $productName;
////                                            if($i!=count( $dishName['product']))
////                                            echo ',';
////
////
////                                        }
////                                        echo ')';
////                                    }
////                                    echo '</div>';
//                                                echo '</div>';
//                                                echo '</div>';
//                                            }
//                                        }
//                                        echo '</div>';
//                                    }
//
//                                }
//                            }
//                        }
//                    }
                    ?>
                </div>
            </div>
</div>
<table style="display:none">
    <?php
    foreach($productTable as $product)
    {
        ?>
        <tr>
            <td><?=$product?></td>
        </tr>
    <?php
            }
    ?>
    </table>