<?php
use backend\modules\disorder\assets\DisorderAsset;
DisorderAsset::register($this);
?>
<div class="disorderActions">
    <a href="/admin/disorder/" class="btn btn-primary flagReturn" flagReturn="">Разнорядка</a>
    <a href="/admin/disorder/" class="btn btn-primary flagReturn" flagReturn="1">Возвраты</a>
    <a href="#" onclick="window.print();" class="btn btn-primary printDishorder">Распечатать<div style="margin-left:20px" class="glyphicon glyphicon-print"></div></a>
</div>
<div class=" print printContain">

    <?php
    foreach ($arBuffets as $bufet) {

        $bufetTable = ' 
    <h1>' . $bufet['bufet_name'] . '</h1><div class="thisDatePrint">' . $date . '</div>
<table class=" print table table-striped disorderTable ' . $tableClass . '" flagReturn="' . $flagReturn . '">
    <thead class="thead-striped">
    <th style="    width: 10px;">№</th>
    <th>Наименование</th>
    <th class="alignCenter">Вес</th>
    <th class="alignCenter">Цена</th>
    <th class="alignCenter">Количество</th>
    <th class="alignCenter">Сумма</th>
    <th class="alignCenter">Возврат</th>
    <th class="alignCenter">Сумма</th>
    </thead>';
        $itogoQuant = 0;
        $itogo = 0;
        $typesCount = 0;
        foreach ($arDishes as $typeName => $dishes) {

            $tableRows[0] = '    
        <tr>
            <td class="dishType" colspan="8">' . $typeName . '</td>
        </tr>';

            foreach ($dishes as $dishKey => $dish) {
                $count = 0;
                $rowCount = 0;
                foreach ($bufet['buffetsOrders'] as $order) {
                    if ($order['dish_id'] == $dish['id']) {

                        $count = $order['count'];
                        $rowCount++;
                        $sum = $dish['dishCost']['cost'] * $count;
                        $tableRows[$dish['id']] = '
                        <tr>
                        <td>' . $i . '</td>
                        <td class="alignLeft tdDishName">' . $dish['name'] . '</td>
                        <td class="alignCenter tdDishWeight">' . $dish['dishCost']['weight'] . '</td>
                        <td class="alignCenter tdDishCost">' . $dish['dishCost']['cost'] . '</td>
                        <td class="alignCenter tdDishCount">' . $count . '</td>
                        <td class="alignCenter tdDishSum ">' . $sum . '</td>
                        <td></td>
                        <td></td>
                        </tr>';
                        $itogoQuant += $count;
                        $itogo += $sum;
                    }
                }
                if ($rowCount) {
                    foreach ($tableRows as $row) {
                        $bufetTable .= $row;
                    }
                    $typesCount++;
                    $tableRows = '';
                }


            }

        }
        $bufetTable .= '<tr class="itogotable"> 
    <td class="alignCenter" colspan="3"></td>
    <td> ИТОГО</td>
    <td class="alignCenter itogoQuant">' . $itogoQuant . '</td>
    <td class="alignCenter itogoSum">' . $itogo . '</td>
    <td></td>
    <td></td>
</tr>';
        $bufetTable .= '</table>';
        if ($typesCount > 0) {
            echo $bufetTable;
        }

    }


//    print_r($arBuffets);
    $bufetTable = ' 
    <div class="thisDatePrint">' . $date . '</div>
<table class=" print table table-striped disorderTable ' . $tableClass . '" flagReturn="' . $flagReturn . '">
    <thead class="thead-striped">
    <th style="    width: 10px;">№</th>
    <th style="
    min-width: 356px;
">Наименование</th>
    <th class="alignCenter">Вес</th>';
    foreach ($arBuffets as $bufet) {
        $bufetTable .= ' <th class="alignCenter">' . $bufet['bufet_name'] . '</th>';
    }
    $bufetTable .= '<th class="alignCenter">Всего</th>
        </thead>';
    $itogoQuant = 0;
    $itogo = 0;
    $typesCount = 0;
    $i=0;
    foreach ($arDishes as $typeName => $dishes) {

        $tableRows[0] = '    
        <tr>
            <td class="dishType" colspan="10">' . $typeName . '</td>
        </tr>';

        foreach ($dishes as $dishKey => $dish) {
            $i++;
            $tableRows[$dish['id']] = '
                            <tr>
                        <td>' . $i . '</td>
                        <td class="alignLeft tdDishName">' . $dish['name'] . '</td>
                        <td class="alignCenter tdDishWeight">' . $dish['dishCost']['weight'] . '</td>';


            $count = 0;
            $rowCount = 0;
            $itogoQuant=0;
            foreach ($arBuffets as $bufet) {

                $count = 0;

                foreach ($bufet['buffetsOrders'] as $order) {
                    if ($order['dish_id'] == $dish['id']) {
                        $count = $order['count'];
                        $rowCount++;
                        $itogoQuant+=$count;
                    }
                }

                $tableRows[$dish['id']] .= ' <td class="alignCenter tdDishCount">' . $count . '</td>';

            }
            $tableRows[$dish['id']] .= '
                            <td>'.$itogoQuant.'</td></tr>';




               if (!$itogoQuant)
                {
                    unset($tableRows[$dish['id']]);
                }
                else
                {

                    foreach ($tableRows as $row) {
                        $bufetTable .= $row;
                    }
                $typesCount++;
                $tableRows = '';
            }
            $itogoQuant=0;


        }
    }
    $bufetTable .= '<tr class="itogotable"> 
    <td class="alignCenter" colspan="5"></td>
    <td> ИТОГО</td>
    <td class="alignCenter itogoQuant">' . $itogoQuant . '</td>
    <td class="alignCenter itogoSum">' . $itogo . '</td>
    <td></td>
    <td></td>
</tr>';
    $bufetTable .= '</table>';
    //    if($typesCount>0)
    //    {
    echo $bufetTable;
    //    }


    ?>
</div>