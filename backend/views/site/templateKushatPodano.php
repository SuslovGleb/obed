<?php

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\controllers\Svg;
use \yii\helpers\Url;
use vova07\imperavi\Widget;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

$this->title = 'My Yii Application';

$svg=new Svg;

$images['2']=$svg->Sup('rgb(181, 102, 56)');
$images['4']=$svg->Garnir('rgb(189, 100, 47)');
$images['1']=$svg->Salat('rgb(7, 206, 64)');
$images['3']=$svg->Meet('rgb(181, 102, 56)');
$images['5']=$svg->Vipechka('rgb(206, 124, 7)');
session_start();
//print_r($arComplexes);
//print_r($sssss);
//print_r($arComplexes);
?>

<div id="content">
    <div class="controller_tab">
        <div class="complexes_line glyphicon glyphicon-th-list <?=$_SESSION['complexBox']=='box'?'':'active';?>"></div>
        <div class="complexes_block glyphicon glyphicon-th <?=$_SESSION['complexBox']=='box'?'active':'';?>"></div>
    </div>

    <?php
        foreach ($arComplexes as $complexKey=>$complexVal)
        {
            ?>
            <div class="complex-box <?=$_SESSION['complexBox']=='box'?'block':'line';?>">
                <div class="complex-title">
                    <h2 class="complex-name"><?=$complexVal['name']?></h2>
                    <h2 class="complex-price"><?=$complexVal['price']?> р</h2>
                </div>
                <div class="complex-ingridients" >
                    <?php
                    $i=0;
                    foreach ($complexVal['dishes'] as $key=>$dish)
                    {$i++;?>
                        <!--<div class="ingridient" style="background-image: url('<?=$dish['typeImage']?>');background-size: 100%;">-->
                        <div class="ingridient" dish_type="<?=$dish['type_id']?>" complex_id="<?=$complexVal['id']?>">
                            <div class="dish_name"><?=$dish['typeName']?></div>
                            <div class="dish_image"><?=$images[$dish['type_id']]?></div>
                            <div class="weight">Вес: <?=$dish['weight']*100?>гр</div>
                        </div>

                    <?}
                    if((int)$i<5)
                    {

                         for ($j = $i; $j < 5; $j++) {
                        ?>
<!--                            <div class="ingridient disabled"></div>-->
                        <?}
                    }
                    ?>

                </div>
                <div class="complex-buy">
                    <div class="basket btn btn-primary">Добавить к заказу</div>
                    <div class="buy btn btn-warning">Оформить заказ</div>

                </div>
            </div>
    <?
        }
    ?>

<script>

    function blocks(div) {
        countBoxes=Math.floor($(window).width()/$(div+':first').outerWidth());

        if(countBoxes>1)
        {
            $i=1;
            $j=0;
            max=0;
            $(div).each(function( index, element ) {
                //element == this
                if($( element ).height()>max)
                {
                    max=$( element ).height();
                }

                $i++;
                if($i>countBoxes || index === ($( div ).length - 1))
                {
                    $(div).slice($j*countBoxes, ($j+1)*countBoxes).height(max);
                    $i=1;$j++;max=0;
                }
            });
        }
        else
        {
            $(div).height('');
        }
    }

    $( document ).ready(function() {
        blocks('.complex-box');
    });

    $(window).resize(function() {
        blocks('.complex-box');
    });

    $('body').on('click','.ingridient',function()
    {
        complex_id=$(this).attr('complex_id');
        dish_type_id=$(this).attr('dish_type');

        $.post('/admin/site/complex-dishes',{ complex_id: complex_id, dish_type_id: dish_type_id }).success(function (data) {
             console.log(data);
        });
    });

    $('body').on('click','.complexes_block,.complexes_line',function()
    {
        if(!$(this).hasClass('active'))
        {
            $('.complex-box').toggleClass('block');
            $('.complex-box').toggleClass('line');
            $('.complexes_line').toggleClass('active');
            $('.complexes_block').toggleClass('active');

            $.post('/admin/site/session-complex').success(function (data) {
               // console.log(data);
            });
            blocks('.complex-box');
        }
    });

    
</script>