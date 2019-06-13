<?php

use common\widgets\DishInputNum;

use yii\helpers\Html;
use backend\modules\disorder\assets\DisorderAsset;
DisorderAsset::register($this);
echo '<div class="disorderActions">
            <a href="#" onclick="window.print();" class="btn btn-primary printDishorder">Распечатать<div style="margin-left:20px" class="glyphicon glyphicon-print"></div></a>
            </div>';


echo Html::csrfMetaTags();
//    print_r($arBuffets);
echo '<h1 style="
    margin:  0 auto;
    width:  10px;
">'.$arBuffets[0]['bufet_name'].'</h1> <div class="thisDatePrint">'.$date.'</div>';

foreach ($arBuffets as $bufet) {

    $bufetTable=' 

<table class=" print table table-striped disorderTable '. $tableClass .'" flagReturn="'. $flagReturn .'">
    <thead class="thead-striped">
    <th style="    width: 10px;">№</th>
    <th style="
    min-width: 356px;
">Наименование</th>
    <th class="alignCenter">Вес</th>
    <th class="alignCenter">Цена</th>
    <th class="alignCenter">Количество</th>
    <th class="alignCenter">Сумма</th>
    <th class="alignCenter">Возврат</th>
    <th class="alignCenter">Сумма</th>
    </thead>';

    $typesCount=0;
    foreach ($arDishes as $typeName => $dishes) {

        $tableRows[0]='    
        <tr>
            <td class="dishType" colspan="8">'.$typeName.'</td>
        </tr>';

        foreach ($dishes as $dishKey => $dish) {
            $count=0;
            $rowCount=0;
            foreach ($bufet['buffetsOrders'] as $order) {
                if ($order['dish_id'] == $dish['id']) {
                    $cellAlready=0;
                    $orderCount = $order['count'];
                    $rowCount++;

                    $quantOrdId=$bufet['id'].$dish['id'];
                    $inputOptions='calculate="retCalculate" ordCalculate="'.$quantOrdId.'" flagReturn="" bufet_id="'.$bufet['id'].'" dish_id="'.$dish['id'].'" date="'.$date.'"';


                    $value=$orderCount;

                    $InputWidget= DishInputNum::widget(['inputOptions'=>$inputOptions,'value'=>$value,'quantId'=>$quantOrdId]);

                    $ordSum=$orderCount*$dish['dishCost']['cost'];
                    $itogoOrd+=$ordSum;
                    $tableRows[$dish['id']]='
                        <tr>
                        <td>'. $i .'</td>
                        <td class="alignLeft tdDishName">'. $dish['name'] .'</td>
                        <td class="alignCenter tdDishWeight">'. $dish['dishCost']['weight'] .'</td>
                        <td class="alignCenter tdDishCost" costQuant="'.$quantOrdId.'">'. $dish['dishCost']['cost'] .'</td>
                        
                        <td class="alignCenter tdDishCount">'. $InputWidget .'</td>
                        <td class="alignCenter tdDishSum" ordSum="'.$quantOrdId.'">'. $ordSum .'</td>';

                    if(count($bufet['buffetsReturn']))
                    {
                        foreach ($bufet['buffetsReturn'] as $return) {

                            $quantId='ret'.$return['bufet_id'].$dish['id'];
                            $inputOptions='calculate="ordCalculate" retCalculate="'.$quantOrdId.'" flagReturn="1" bufet_id="'.$bufet['id'].'" dish_id="'.$dish['id'].'" date="'.$date.'"';

                            if ($return['dish_id'] == $dish['id']) {


                                $returnCount = $return['count'];
                                $value=$returnCount;

                                $InputWidget= DishInputNum::widget(['inputOptions'=>$inputOptions,'value'=>$value,'quantId'=>$quantId]);




                                $tableRows[$dish['id']].= ' <td>' . $InputWidget . '</td>';
                                $cellAlready=1;
                            }


                        }
                        if(!$cellAlready)
                        {

                            $value=0;

                            $InputWidget= DishInputNum::widget(['inputOptions'=>$inputOptions,'value'=>$value,'quantId'=>$quantId]);



                            $tableRows[$dish['id']].= ' <td>' . $InputWidget . '</td>';

                        }
                    }
                    else
                    {

                        $quantOrdId='ret'.$bufet['id'].$dish['id'];
                        $inputOptions='calculate="ordCalculate" retCalculate="'.$quantOrdId.'" flagReturn="1" bufet_id="'.$bufet['id'].'" dish_id="'.$dish['id'].'" date="'.$date.'"';


                        $value=0;

                        $InputWidget= DishInputNum::widget(['inputOptions'=>$inputOptions,'value'=>$value,'quantId'=>$quantOrdId]);

                        $tableRows[$dish['id']].= ' <td>' . $InputWidget . '</td>';
                    }

//                        $sum=($orderCount-$returnCount)*$dish['dishCost']['cost'];
                    $sum=$returnCount*$dish['dishCost']['cost'];
                    $itogo+=$sum;
                    $tableRows[$dish['id']].= ' <td class="alignCenter" retSum="'.$quantOrdId.'">'.$sum.'</td>';
                    $tableRows[$dish['id']].=' </tr>';
                    $returnCount=0;
                    $orderCount=0;
                }
            }

            if($rowCount) {
                foreach ($tableRows as $row) {
                    $bufetTable.= $row;
                }
                $typesCount++;
                $tableRows='';
            }


        }

    }
    $bufetTable.='<tr class="itogotable"> 
    <td class="alignRight" > Прибыль</td>
    <td class="alignLeft itogoSum profit">'. ($itogoOrd - $itogo) .'</td>

    <td colspan="2"></td>
    <td class="alignRight" > ИТОГО</td>
    <td class="alignCenter itogoSum order">'.$itogoOrd.'</td>
    
    <td class="alignRight" > ИТОГО</td>
    <td class="alignCenter itogoSum return">'.$itogo.'</td>
</tr>';
    $bufetTable.='</table>';

    if($typesCount>0)
    {

        echo '<div class="printContain">'.$bufetTable.'</div>';
    }

}