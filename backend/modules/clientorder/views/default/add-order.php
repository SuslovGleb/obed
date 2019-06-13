<?php

use backend\modules\clientorder\assets\ClientorderAsset;
use backend\controllers\Svg;
use yii\helpers\Html;

$bundle = ClientorderAsset::register($this);

?>
<style>

    .flyerDay {
        /*padding: 10px;*/
    }

    .flyerDay::after {
        content: "";
        background: rgba(0, 0, 0, 0) url(/admin/assets/b6034794/images/free-lunch-vector.jpg) no-repeat !important;
        opacity: 0.3;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        position: fixed;
        z-index: -1;
        background-size: 100% !important;
    }

    .flyerRow .dish .dishName.grey
    {
        text-shadow: 0 0 0 grey!important;
        color: grey!important;
        cursor: not-allowed;
    }
    .flyerRow .dish .dishName.ComplexGrey
    {
        text-shadow: 0 0 0 grey!important;
        color: grey!important;
        cursor: not-allowed;
    }
    .products
    {
        display: none;
    }
    /*.dishBox:hover .products*/
    /*{*/
        /*display: block;*/
        /*font-size: 14px;*/
    /*}*/

    tr.tabeDishes
    {
        cursor: pointer;
    }
    tr.tabeDishes[typeid='1'] {
        background-color: #deffe5;
    }tr.tabeDishes[typeid='2'] {
        background-color:#ffe2e2;
    }tr.tabeDishes[typeid='3'] {
        background-color: #ffe7fa;
    }
    tr.tabeDishes[typeid='4'] {
        background-color: #d8fffc;
    }tr.tabeDishes[typeid='3'][additional_typeid='4'] {
        background-color: #7de8e0;
    }
    tr.tabeDishes[typeid='5'] {
        cursor: unset;
        background-color: burlywood;
    }
    td.operations {
        padding: 0!important;
    }
    div.operatons {
        width: 25%;
        height: 25px;
        float: left;
        background-color: #00000030;
        padding: 0;
        line-height: 25px;
        margin: 0;
        border-radius: 25%;
    }
    div.operatons:hover {
        background-color: #0a800a;
    }div.operatons.glyphicon-remove:hover {
        background-color: #f36772;
    }
    .dishBox:hover
    {
        cursor: pointer;
        background-color: #ecbe83;
    } .dishBox.ComplexChosen:hover
    {
        cursor: pointer;
        background-color: #83ec8a;
    }.dishBox.grey:hover
    {
        cursor: not-allowed;
        background-color: unset;
    }
    .dishBox.ComplexGrey:hover
    {
        cursor: not-allowed;
        background-color: unset;
    }
    tr.selectForComplex
    {
        background-color: #0a800a!important;
    }
    tr.excessDish
    {
        background-color: #af4300!important;
    }
    .ComplexChoseBox.choses
    {
        background-color: #00ff5a;
    }
    .ComplexChosen .dishName{
        text-shadow: none;

    }
    .ComplexChosen {
        background-color: #bdffc7;
        color: black;
    }
    .ComplexChoseBox.notFits {
        background-color: grey!important;
    }
    .ComplexChoseBox.canChose {
        background-color: #0d8011;
    }
    .ComplexChoseBox.canNotChose {
        background-color: #807710;
    }
    .clientChoose{
        float: left;
    }
    .search_result {
        width: 387px;
        font-size: 10pt;
        height: 100px;
        background-color: #ffffffe0;
        border-radius: 20px;
        position: absolute;
        padding: 13px;
        overflow-y: scroll;
        display: none;
        z-index: 3;
    }
    .search_result li
    {
        cursor: pointer;
    }
    .search_result li:hover
    {
        background-color: grey;
    }
    .search_result li.selected
    {
        background-color: grey;
    }
</style>
<?php

//print_r($types)
?>
<div class=" print printContain">
    <div class="ComplexesChoseBox">
        <div class="input-group">
            <input class="search inputMain form-control" type="text" name="clientChoose"
                   placeholder="Выберите клиента">
            <span class="glyphicon  glyphicon-search"></span>
        </div>
        <?php

        foreach ($complexes as $id=>$complex)
        {

//            print_r($complex);
//            foreach ($complex->dishComposes as $key=>$dish)
//            {
//                print_r($dish->id);
//            }

            echo '<div class="ComplexChoseBox" complex_id="'.$complex['id'].'"';
            foreach ($complex->complexMenus as $complexMenu)
            {
                echo 'dish-type'.$complexMenu->type_id.'=""';
//                print_r($complexMenu->type_id);
            }
            echo 'complexPrice="'.$complex->price.'"';
            echo 'complexName="'.$complex->name.'"';
            echo '>'.$complexShort[$complex['name']].'</div>';
        }
        ?>

    </div>
    <div class="flyerDay addClientOrder">
        <div class="flyerRow row1" style="padding: 5px;width: 27%">

            <?php
            foreach ($types as $typeId => $dishes) {
                $typeId=trim($typeId);
                if ($typeId < 4) {
                    if($typeId==3)
                    {
                        $type_id='type_id="'.$typeId.'" additionalType="4"';
                    }
                    else
                    {
                        $type_id='type_id="'.$typeId.'" ';
                    }
                    echo '<div class="type"'.$type_id.'>';
                    echo '<div class="typeName">';
                    echo $dishes['typeName'];
                    echo '</div>';
                    foreach ($dishes as $dishKey => $dish) {


                        if($dishKey!='typeName') {


                            echo '<div class="dishBox"';
                            echo 'complex_ids="';
                            $i=0;
                            foreach ($dish['dishComplexes'] as $dishComplex) {
                                $i++;
                                if(count($dish['dishComplexes'])==$i)
                                {
                                    echo $dishComplex->id;
                                }
                                else
                                {
                                    echo $dishComplex->id.',';
                                }
                                //echo ($dishComplex->name);
                            }
                            echo '" typeId="'.$typeId.'" id="' . $dishKey . '" cost="' . $dish['cost'] . '" weight="' . $dish['weight'] . '">';
                            echo '<div class="dish">';
                            echo '<div class="dishName ">' . $dish['name'] . '</div>';
                            echo '<div class="dishCost">';
                            echo '                            <span style="    margin-right: 3px;">' . $dish['cost'] . 'р</span> ';
                            echo '                            <span style="float: right;">' . $dish['weight'] . 'г</span>';
                            echo '                        </div>';
                            echo ' <div class="products">';

                            if (count($dish['products'])) {
                                echo '(';
                                $i = 0;
                                foreach ($dish['products'] as $key=>$product) {
                                    $i++;


                                    echo $product;
                                    if ($i != count($dishName['product']))
                                        echo ',';
                                }
                                echo ')';
                            }
                            echo ' </div>';
                            echo ' </div>';
                            echo '</div>';
                        }
                    }


                    echo '</div>';
                }
            }


            ?>

        </div>
        <div class="flyerRow row2 " style="padding: 5px;width: 27%">
            <?php
            foreach ($types as $typeId => $dishes) {
                $typeId=trim($typeId);
                if ($typeId >=4&&$typeId <6) {
                    if($typeId==4)
                    {
                        $type_id='type_id="'.$typeId.'" additionalType="3"';
                    }
                    else
                    {
                        $type_id='type_id="'.$typeId.'"';
                    }
                    echo '<div class="type"'.$type_id.'>';
                    echo '<div class="typeName">';
                    echo $dishes['typeName'];
                    echo '</div>';
                    foreach ($dishes as $dishKey => $dish) {

                        if($dishKey!='typeName') {
                            echo '<div class="dishBox"';
                            echo 'complex_ids="';
                            $i=0;
                            foreach ($dish['dishComplexes'] as $dishComplex) {
                                $i++;
                                if(count($dish['dishComplexes'])==$i)
                                {
                                    echo $dishComplex->id;
                                }
                                else
                                {
                                    echo $dishComplex->id.',';
                                }
                                //echo ($dishComplex->name);
                            }
                            echo '" typeId="'.$typeId.'" id="' . $dishKey . '" cost="' . $dish['cost'] . '" weight="' . $dish['weight'] . '">';
                            echo '<div class="dish">';
                            echo '<div class="dishName ">' . $dish['name'] . '</div>';
                            echo '<div class="dishCost">';
                            echo '                            <span style="    margin-right: 3px;">' . $dish['cost'] . 'р</span> ';
                            echo '                            <span style="float: right;">' . $dish['weight'] . 'г</span>';
                            echo '                        </div>';
                            echo ' <div class="products">';

                                if (count($dish['products'])) {
                                    echo '(';
                                    $i = 0;
                                   foreach ($dish['products'] as $key=>$product) {
                                        $i++;


                                        echo $product;
                                        if ($i != count($dishName['product']))
                                            echo ',';
                                    }
                                    echo ')';
                                }


                            echo ' </div>';
                            echo ' </div>';
                            echo '</div>';
                        }
                    }


                    echo '</div>';
                }
            }


            ?>

        </div>
        <div class="flyerRow row3" style="width: 46%;    padding: 0;">
            <table class="order_table" style="width: 100%">
                <tbody>
                <tr class="tableTitles">
                    <th>Наименование</th>
                    <th>Цена</th>
                    <th>Кол-во</th>
                    <th>Сумма</th>
                    <th>Операции</th>
                </tr>
                <tr id="tableTotal">
                    <td colspan="3">ИТОГО</td>
                    <td id="tableSum"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>