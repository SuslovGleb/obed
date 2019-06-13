<?php

use backend\modules\clientorder\assets\ClientorderAsset;
use backend\controllers\Svg;

ClientorderAsset::register($this);
//echo '<pre>';
//
//print_r($complexes);
//echo '</pre>';

//echo '<pre>';
//
//print_r($allDishesbyDay);
//echo '</pre>';

$products=[];

?>

<div class=" print printContain">

    <?php
    foreach ($allDishesbyDay as $day => $Dishes) {
        $types = [];
        foreach ($Dishes as $key => $dish) {
            $types[$i][$dish['dishType']['type']]['type_id'] = $dish['dishType']['id'];
            $types[$i][$dish['dishType']['type']][$dish['id']]['name'] = $dish['name'];
            $types[$i][$dish['dishType']['type']][$dish['id']]['cost'] = $dish['dishCost']['cost'];
            $types[$i][$dish['dishType']['type']][$dish['id']]['weight'] = $dish['dishCost']['weight'];
            foreach ($dish['dishProducts'] as $key => $product) {
                if ($product['product']['productSynonyms']['client_synonym'])
                {
                    $types[$i][$dish['dishType']['type']][$dish['id']]['product'][] = $product['product']['productSynonyms']['client_synonym'];
                }

            }
//

        }
//                echo '</pre>';echo '<pre>';
//
////print_r($types);
//                echo '</pre>';
        foreach ($types as $dayNum => $type) {
//                    echo '<pre>';
//
//                    print_r($types);
//                    echo '</pre>';
            ?>
            <div class="flyerDay">
                <div class="dayName"><?= $day ?></div>
                <div class="flyerRow row1">
                    <?php
                    $complexDisesId = [];
                    foreach ($type as $typeName => $Dish) {


                        if (
                                   $Dish['type_id'] == 1
                                || $Dish['type_id'] == 2
                                || $Dish['type_id'] == 3
                                || $Dish['type_id'] == 4
                        ) {
                            echo '<div class="type">';
                            echo '<div class="typeSvg">'. $small_images[$Dish['type_id']] . '</div>';
                            echo '<div class="typeName">'. $typeName . '</div>';
                            $N = 0;

                            foreach ($Dish as $dishKey => $dishName) {
                                if ($dishKey != 'type_id') {

                                    $N++;
                                    $complexDisesId[$dishKey]['N'] = $N;
                                    $complexDisesId[$dishKey]['Name'] = $dishName['name'];
                                    echo '<div class="dishBox">';
                                    echo '<div class="dish">
                                            <div class="dishNum">№' . $N . ':</div>
                                            <div class="dishName">' . $dishName['name'] . '</div>';
                                    echo '<div class="dishCost" style="width: 24%;">
                                                        <span style="    margin-right: 3px;">' . $dishName['cost'] . 'р</span>
                                                        /
                                                        <span style="float: right;">' . $dishName['weight'] . 'г</span>
                                                    </div>';

                                    if (count($dishName['product'])) {
                                        $dishProduct='(';
                                        $i=0;
                                        foreach ($dishName['product'] as $key => $productName) {
                                            $i++;
                                            $dishProduct.= $productName;
                                            if($i!=count( $dishName['product']))
                                            {
                                                $dishProduct.= ',';
                                            }
                                        }
                                        $dishProduct.= ')';
                                    }
                                    $products[$typeName][$dishName['name']]=$dishProduct;
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
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            echo '</div>';
                        }

                    }

                    ?>
                </div>
                <div class="flyerRow row2">
                    <?php

//                    var_dump($complexes);
                    foreach ($complexes as $complex) {

                        if(
                                stristr($complex->name, 'ланч') == true
                            ||  stristr($complex->name, 'Ланч') == true
                          )
                        {
                            echo '<div class="complexBox">';
                            echo '<div class="complexNameBox">';
                            echo '<div class="complexName">' . $complex->name . '</div>';
                            echo '<div class="complexPrice">' . $complex->price . ' руб.</div>';
                            echo '</div>';
                            $comI=0;
                            foreach ($complex->complexMenus as $complexMenu) {
                                $weight = $complexMenu->weight;
                                $comI++;
                                $comI==1?$style='style="margin-top:20px;"':$style='';
                                echo '<div class="complexType">' . $complexMenu->type->type;
                                echo '</div>';
                                echo '<div class="complexTypeSvg" '.$style.'>'. $small_images[$complexMenu->type_id] . '</div>';
                                echo '<div class="complexDishes">';
                                echo '<span style="width: 58%;">';
                                echo '(';
                                $complexDises = [];
//print_r($type);
                                $conmpexDishCount=0;
                                foreach ($complex->dishComposes as $compose) {
                                    if ($compose->dish->type == $complexMenu->type_id) {
                                        if ($complexDisesId[$compose->dish->id]) {
                                            $conmpexDishCount++;
                                            // print_r($complexMenu->type->type);
                                          $complexDises[] = //$complexDisesId[$compose->dish->id]['N'];
                                            $complexDisesId[$compose->dish->id]['Name'];
//                                echo '№'.$complexDisesId[$compose->dish->id]['N'].',';

                                        }


                                    }
                                }
                                if($conmpexDishCount==(count($type[$complexMenu->type->type])-1))
                                {
                                    echo 'Любой на выбор) </div>';
                                }
                                else
                                {

                                    sort($complexDises);
                                    // echo '№' . implode("/ №", $complexDises);
                                    echo  implode("/ ", $complexDises);
                                    echo ')';
                                    echo '<span>';
                                    echo '</div>';
                                }

                            }
                            echo '</div>';
                        }


                    }
                   
                    ?>


                </div>
                <div class="flyerRow row3">
                    <?php
//                    var_dump($complexes);
                    foreach ($complexes as $complex) {
                        if(
                            stristr($complex->name, 'ланч') == true
                            ||  stristr($complex->name, 'Ланч') == true
                        )
                        {}
                        else
                            {

                            echo '<div class="complexBox">';
                            echo '<div class="complexNameBox">';
                            echo '<div class="complexName">' . $complex->name . '</div>';
                            echo '<div class="complexPrice">' . $complex->price . ' руб.</div>';
                            echo '</div>';
                            $comI = 0;
                            foreach ($complex->complexMenus as $complexMenu) {
                                $weight = $complexMenu->weight;
                                $comI++;
                                $comI == 1 ? $style = 'style="margin-top:20px;"' : $style = '';
                                echo '<div class="complexType">' . $complexMenu->type->type;
                                echo '</div>';
                                echo '<div class="complexTypeSvg" ' . $style . '>' . $small_images[$complexMenu->type_id] . '</div>';
                                echo '<div class="complexDishes">';
                                echo '(';
                                $complexDises = [];
//print_r($type);
                                $conmpexDishCount = 0;
                                foreach ($complex->dishComposes as $compose) {
                                    if ($compose->dish->type == $complexMenu->type_id) {
                                        if ($complexDisesId[$compose->dish->id]) {
                                            $conmpexDishCount++;
                                            // print_r($complexMenu->type->type);
                                            $complexDises[] = //$complexDisesId[$compose->dish->id]['N'];
                                            $complexDisesId[$compose->dish->id]['Name'];
//                                echo '№'.$complexDisesId[$compose->dish->id]['N'].',';
//print_r($complexDises);

                                        }


                                    }
                                }
                                if ($conmpexDishCount == (count($type[$complexMenu->type->type]) - 1)) {
                                    echo 'Любой на выбор) </div>';
                                } else {

                                    sort($complexDises);
                                   // echo '№' . implode("/ №", $complexDises);
                                    echo  implode("/ ", $complexDises);
                                    echo ')';
                                    echo '</div>';
                                }

                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>

            </div>
            <?php
        }
    }
?>
    <div class="flyerDay">
<!--        <div class="dayName">Состав</div>-->
        <?php
        foreach ($products as $productCategory=>$dishes)
        {

            echo '<div class="dishCategoryRow">';
            echo '<div class="productCategory">'.$productCategory.'</div>';
            foreach ($dishes as $dish=>$product)
            {
                echo '<div class="dishProducts"><div class="dishNameProducts">'.$dish.'</div><div class="products">'.$product.'</div></div>';

            }
            if($productCategory=='Гарнир')
            {
                echo '<br>';
                 foreach ($types as $dayNum => $type) {
                 foreach ($type as $typeName => $Dish) {


                        if ($Dish['type_id'] == 5) {
                            echo '<div class="flyerRow  type" style="
    margin: 0;
    padding: 0;
">';
                            echo '<div class="typeSvg">'. $small_images[$Dish['type_id']] . '</div>';
                            echo '<div class="typeName">'. $typeName . '</div>';
                            $N = 0;

                            foreach ($Dish as $dishKey => $dishName) {
                                if ($dishKey != 'type_id') {

                                    $N++;
                                    $complexDisesId[$dishKey]['N'] = $N;
                                    $complexDisesId[$dishKey]['Name'] = $dishName['name'];
                                    echo '<div class="dishBox">';
                                    echo '<div class="dish">';
                                            //<div class="dishNum">№' . $N .
                                         //  </div>
                                            echo ' <div class="dishName">' . $dishName['name'] . '</div>';
                                    echo '<div class="dishCost" style="width: 26%;">
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
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            echo '</div>';
                        }

                    }
            }}
            echo '</div>';
        }
//        echo '<pre>';
//
//        print_r($products);
//        echo '</pre>';
//
        ?>
    </div>



</div>