<?php

use backend\controllers\Svg;
use frontend\models\ComplexModal;
use common\widgets\DishesWidgetNew;
use frontend\modules\complex\assets\ComplexAsset;
$bundle=ComplexAsset::register($this);


$this->title = 'Комплексы';

$svg=new Svg;

$images['1']=$svg->Salat('rgb(7, 206, 64)',95);
$images['3']=$svg->Meet('rgb(245, 222, 179)',85);
$images['2']=$svg->Sup('rgb(245, 222, 179)',85);
$images['4']=$svg->Garnir('rgb(245, 222, 179)',85);
$images['5']=$svg->Vipechka('rgb(245, 222, 179)',90);



$small_images['1']=$svg->Salat('rgb(179, 210, 177)',55);
$small_images['2']=$svg->Sup('rgb(179, 210, 177)',45);
$small_images['3']=$svg->Meet('rgb(179, 210, 177)',45);
$small_images['4']=$svg->Garnir('rgb(179, 210, 177)',45);
$small_images['5']=$svg->Vipechka('rgb(179, 210, 177)',45);




?>

    <div id="content">
    <div id="complexes_box">

        <?php

        $dfsdf=[];
        foreach ($arComplexes as $complexKey=>$complexVal)
        {
            $complexName    = $complexVal->name;
            $complexId      = $complexVal->id;
            
             $complexPrice   = round($complexVal->price*1.25,-1) ;
            //$complexPrice   = $complexVal->price;
            $complexMenu    = $complexVal->complexMenus;


            ?>
            <div complex_cost="<?=$complexPrice?>" complex_id="<?=$complexId?>" complex_name="<?= $complexName ?>" class="complex-box block">
                <div class="curtains"></div>
                <div class="complex-title">
                    <h2 class="complex-name"><?= $complexName ?></h2>
                    <div class="dish_smal_svg_box">
                        <?php

                        $weightCo=0;
                        foreach ($complexMenu as $key=>$type)
                        {


                            echo '<div class="small_dish_hover_box">';
                            while($weightCo < $type->weight)
                            {
                                if($weightCo==0)
                                {
                                    echo '<div class="dish_smal_svg">'.$small_images[$type->type_id]  .'</div>';
                                }
                                else
                                {
                                    echo '<div class="dish_smal_svg double">'.$small_images[$type->type_id]  .'</div>';

                                }

                                $weightCo++;
                            }

                            $weightCo=0;

                            echo '</div>';
                        }



                        ?>
                    </div>
                    <h2 class="complex-price"><?=$complexPrice?> р</h2>
                </div>
                <div class="complex-ingridients" data-complexId="<?=$complexId?>" >
                    <?php
                    $i=0;
                    foreach ($complexMenu as $key=>$type)
                    {

                        $i++;

                        $dishes0  =$type->type->dishes0;


                        $typeImage  =$type->type->image;
                        $typeWeight =$type->weight;
                        $typeName   =$type->type->type;
                        $typeId     =$type->type_id;

                        $dishCount=0;

                        foreach ($dishes0 as $dishes0key=>$dishesVal)
                        {
                            if($dishesVal->actual!=0)
                            {
                                $dishCount++;
                                $dishName=$dishesVal->name;
                                $dishWeight='90';
                                $dish_id=$dishesVal->id;

                                if($dishesVal->image)
                                {
                                    $dishImage=$dishesVal->image;
                                }
                                else
                                {
                                    $dishImage=$images[$typeId];
                                }
                            }
                            ?>

                            <div class="hidden"><pre><? print_r($dishesVal);?></pre></div>
                        <?
                        }

                    if($dishCount>1) {
                        ?>
                        <!--<div class="ingridient" style="background-image: url('<?= $typeImage ?>');background-size: 100%;">-->
                        <div class="ingridient" data-toggle="modal"
                             data-target="#complexModal<?= $complexId . $typeId ?>"
                             dish_weight="<?= $typeWeight * 100 ?>" dish_type_name="<?= $typeName ?>"
                             dish_type="<?= $typeId ?>" complex_id="<?= $complexId ?>">
                            <div class="dish_name"><?= $typeName ?></div>
                            <div class="dish_image"><?= $images[$typeId] ?></div>
                            <!--                        <div class="weight">Вес: --><?//=$dish['weight']*100
                            ?><!--гр</div>-->
                            <div class="weight">
                                <div class="choose">Выбрать</div>
                            </div>
                        </div>

                        <?php
                    }

                    else
                        {


                            ?>

                            <div class="dish_name" style="margin-bottom: -15px;"><?=$dishName?></div>
                            <!--<div class="ingridient" style="background-image: url('<?=$typeImage?>');background-size: 100%;">-->
                            <div class="ingridient" data-toggle="modal"data-target="#complexModal<?=$complexId.$typeId?>"  dish_weight="<?=$typeWeight*100?>" dish_type_name="<?=$typeName?>" dish_type="<?=$typeId?>" complex_id="<?=$complexId?>" dish_name="<?=$dishName?>" dish_id="<?=$dish_id?>">
                                <div class="dish_name" style="margin-bottom: -15px;"><?=$dishName?></div>
                                <div class="dish_image"><?=$dishImage?></div>
                                <!--                        <div class="weight">Вес: --><?//=$dish['weight']*100?><!--гр</div>-->
                                <div class="weight">
                                    <div class="choose">Вес:<?=$dishWeight?>г</div>
                                </div>

                            </div>

                            <?
                            $dishCount=0;
                        }

                        $header='<h1 class="complex_modal_name" >'.$typeName.'</h1>';
                        $modalOptions=[
                            'id' => 'complexModal'.$complexId.$typeId,
                            'header' => $header,
                            'size' => 'modal-lg',
                            'clientOptions'=>
                                [
                                    'show' => false,
                                ]
                        ];


                        $options['type']=$typeId;
                        $options['complexId']=$complexId;
                        $options['additionalDishFlag']=false;


                        $typeDishesList='';
                        foreach ($dishes0 as $dishes0key=>$dishesVal)
                        {
                            $options['order']=true;
                            if(!empty($dishesVal->dishesSoldOuts))
                            {
                                foreach ($dishesVal->dishesSoldOuts as $dishesSoldOuts)
                                {
                                    if($dishesSoldOuts->date==date('Y-m-d'))
                                    {
                                        $options['order']=false;
                                    }
                                }
                            }
                            if($dishesVal->type==$typeId)
                            {

                                foreach ($dishesVal->dishComposes as $dishCompose)
                                {
                                    if($dishCompose->complex_id==$complexId)
                                    {
                                        $options['dish']=$dishesVal;
                                        $options['weightCo']=$type->weight;

                                        $typeDishesList  .=  DishesWidgetNew::widget($options);

                                    }
                                }

                            }

                        }

                        ComplexModal::begin($modalOptions);
//                        foreach ($dishes0 as $dishes0key=>$dishesVal)
//                        {
//                            echo '<div style="display:none">';
//                            print_r($dishesVal);
//                            echo '</div>';
//                        }
                        echo $typeDishesList;
                        ComplexModal::end();
                    }

                    ?>

                </div>
                <div class="complex-buy">
                    <div class="complexChooseText">Выберите блюда, входящие в комплекс</div>

                    <div class="input-group complexBuyCount">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn dishBtnMinus btn-number" data-type="minus" data-field="quant[<?=$complexId?>]">
                                        <span class="glyphicon glyphicon-minus"></span>
                                      </button>
                                  </span>
                        <input type="text" name="quant[<?=$complexId?>]" class="form-control input-number dishInputNum" value="1" min="1" max="99">
                        <span class="input-group-btn">
                                      <button type="button" class="btn btn-number dishBtnPlus" data-type="plus" data-field="quant[<?=$complexId?>]">
                                          <span class="glyphicon glyphicon-plus"></span>
                                      </button>
                                  </span>
                    </div>
                    <div class="btn btnAddComplex inactive">Добавить к заказу</div>
                    <!--                <div class="buy btn btn-warning">Оформить заказ</div>-->

                </div>
            </div>
            <?
        }
        ?>

    </div>
    </div>
<?php
$modalOptions=[
    'complexImage'=>$complexImage,
    'id' => 'userModal'.$complexId,
    'header' => '<div class="complex_modal_name">'.$complexName.'</div>',
    'size' => 'modal-lg',
    'clientOptions'=>
        [
            'show' => false,
        ]
];

ComplexModal::begin($modalOptions);

ComplexModal::end();

?>