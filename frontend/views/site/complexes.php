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
use frontend\models\ComplexModal;

$this->title = 'My Yii Application';

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
<!--    <div class="controller_tab">-->
<!--        <div  view="line" class="complexes_line glyphicon glyphicon-th-list --><?//=$session->get('complexBox')=='block'?'':'active';?><!--"></div>-->
<!--        <div  view="block" class="complexes_block glyphicon glyphicon-th --><?//=$session->get('complexBox')=='block'?'active':'';?><!--"></div>-->
<!--    </div>-->

    <div id="complexes_box">
    <?php
    foreach ($arComplexes as $complexKey=>$complexVal)
    {
    ?>
    <!--        <div class="complex-box --><? //=$_SESSION['complexBox']=='box'?'block':'line';
    ?><!--">-->
<!--    <div complex_cost="--><?//=$complexVal['price']?><!--" complex_id="--><?//=$complexVal['id']?><!--" complex_name="--><?//= $complexVal['name'] ?><!--" class="complex-box --><?//=$session->get('complexBox')=='block'?'block':'line';?><!--">-->
    <div complex_cost="<?=$complexVal['price']?>" complex_id="<?=$complexVal['id']?>" complex_name="<?= $complexVal['name'] ?>" class="complex-box block">
        <div class="curtains"></div>
        <div class="complex-title">
            <h2 class="complex-name"><?= $complexVal['name'] ?></h2>
            <div class="dish_smal_svg_box">
                <?php

                    $weightCo=0;
                    foreach ($complexVal['dishes'] as $key=>$dish)
                    {
                        echo '<div class="small_dish_hover_box">';
                        while($weightCo < $dish['weight'])
                        {
                            if($weightCo==0)
                            {
                                echo '<div class="dish_smal_svg">'.$small_images[$dish['type_id']]  .'</div>';
                            }
                            else
                            {
                                echo '<div class="dish_smal_svg double">'.$small_images[$dish['type_id']]  .'</div>';

                            }

                            $weightCo++;
                        }

                        $weightCo=0;

                        echo '</div>';
                    }



                ?>
                </div>
                <h2 class="complex-price"><?=$complexVal['price']?> р</h2>
            </div>
            <div class="complex-ingridients" data-complexId="<?=$complexVal['id']?>">
                <?php
                $i=0;
                foreach ($complexVal['dishes'] as $key=>$dish)
                {$i++;?>
                    <!--<div class="ingridient" style="background-image: url('<?=$dish['typeImage']?>');background-size: 100%;">-->
                    <div class="ingridient" dish_weight="<?=$dish['weight']*100?>" dish_type_name="<?=$dish['typeName']?>" dish_type="<?=$dish['type_id']?>" complex_id="<?=$complexVal['id']?>">
                        <div class="dish_name"><?=$dish['typeName']?></div>
                        <div class="dish_image"><?=$images[$dish['type_id']]?></div>
<!--                        <div class="weight">Вес: --><?//=$dish['weight']*100?><!--гр</div>-->
                        <div class="weight">
                            <div class="choose">Выбрать</div>
                        </div>
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
                <div class="complexChooseText">Выберите блюда, входящие в комплекс</div>

                <div class="input-group complexBuyCount">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn dishBtnMinus btn-number" data-type="minus" data-field="quant[<?=$complexVal['id']?>]">
                                        <span class="glyphicon glyphicon-minus"></span>
                                      </button>
                                  </span>
                    <input type="text" name="quant[<?=$complexVal['id']?>]" class="form-control input-number dishInputNum" value="1" min="1" max="99">
                    <span class="input-group-btn">
                                      <button type="button" class="btn btn-number dishBtnPlus" data-type="plus" data-field="quant[<?=$complexVal['id']?>]">
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
    <script>





        $('body').on('click','.complexes_block,.complexes_line',function()
        {
            if(!$(this).hasClass('active'))
            {
                $('.complex-box').toggleClass('block');
                $('.complex-box').toggleClass('line');
                $('.complexes_line').toggleClass('active');
                $('.complexes_block').toggleClass('active');
                view=$(this).attr('view');
                blocks('.complex-box');
                $.post( '/site/session-complex',{'view':view,'<?= Yii::$app->request->csrfParam ?>' : '<?= Yii::$app->request->getCsrfToken()?>'}).done(function (data) {
                     console.log(data);
                });

            }
        });


        $('body').on('click','.btnAddComplex',function() {
            if(!$(this).hasClass('inactive')) {
                arComplex = [];
                arComplexDishes = [];

                ingridients = $(this).closest(".complex-box").find('.ingridient');

                complex_box=$(this).closest('.complex-box');
                complex_id =  complex_box.attr('complex_id');
                complex_name =complex_box.attr('complex_name');
                complex_cost =complex_box.attr('complex_cost');

                arComplex['name'] = complex_name;
                arComplex['id'] = complex_id;
                arComplex['cost'] = complex_cost;


                ingridients.each(function (index) {

                    dish_name = $(this).attr('dish_name');
                    dish_type_name = $(this).attr('dish_type_name');
                    dish_id = $(this).attr('dish_id');
                    dish_type_id = $(this).attr('dish_type');
                    dish_weight = $(this).attr('dish_weight')
                    arName = [];


                    arName['dish_name'] = dish_name;
                    arName['dish_id'] = dish_id;

                    if(!$(this).hasClass('hasReplaced')) {
                        arComplexDishes['dish' + dish_type_id] = arName;
                    }
                    $(this).find('.dish_image').find('img').remove();
                    $(this).find('.dish_image').find('svg').show();
                    $(this).find('.weight').html('<div class="choose">Вес: ' + dish_weight + 'г</div>');
                    $(this).find('.dish_name').text(dish_type_name);

                    $(this).attr('dish_id', '');
                    $(this).css('background-color', '');
                    $(this).find('.dish_name').css('margin-bottom', '');
                    // $(element).css('color','');
                    $(this).find('.dish_image').css('margin-left', '');
                    $(this).removeClass('choosen');
                    $(this).removeClass('hasReplaced');
                    $(this).removeAttr('replacedby');
                    $(this).removeAttr('dish_replacement');
                    // console.log(element);
                });
                $(this).addClass('inactive');
                arComplex['dishes'] = arComplexDishes;
                complex_name = arComplex['name'];
                complex_id = arComplex['id'];
                complex_cost = arComplex['cost'];
                attr = 'complex_attr="';
                dishesSumName = '';


                attr_val='';
                for (var value in arComplex['dishes'])
                {
                    if(value) {
                        attr_val += arComplex['dishes'][value]['dish_id'] +',';
                        //attr += value + '="' + arComplex['dishes'][value]['dish_id'] + '" ';
                        dishesSumName += arComplex['dishes'][value]['dish_name'] + ' + ';
                    }
                }

                dishesSumName=dishesSumName.substring(0, dishesSumName.length - 3);
                final_name=complex_name+' ('+dishesSumName+')';

                attr_val+=complex_id;
                attr+=attr_val+'"';



                num= $('#order_table tr').length;
                count=complex_box.find('.dishInputNum').val()
               // count=1;
                sum= parseInt(count) * parseInt(complex_cost);
                can_add_row=1;
                final_cost_text=complex_cost;
                final_cost_val=complex_cost;
                is_false=0;
                $('tr:not(:first)', $('#order_table')).each(function(column,tr)
                {
                    console.log(attr_val,'attr_val');
                    console.log($(this).attr('complex_attr'),'$(this).attr(\'complex_attr\')');

                    if(attr_val==$(this).attr('complex_attr'))
                    {
                        addDishToExist(tr);

                    }

                });

        AddRowToDetailCart(can_add_row, attr, num, final_name, final_cost_text, count, final_cost_val);


                complexBox.find('.complexChooseText').show();
                complexBox.find('.complexBuyCount ').hide();


            }
        });

        $('body').on('click','.dish_box .btnAddDish',function() {

            ingridient=             $('.ingridient.active');
            dish_type=              ingridient.attr('dish_type');
            ingridient_box=         ingridient.closest('.complex-ingridients');
            dish_box=               $(this).closest('.dish_box');
            dish_name=              dish_box.attr('value');
            dish_id=                dish_box.attr('dish_id');
            dish_weight=            dish_box.find('.dish_weight').text();
            dish_image=             dish_box.find('.dish_image').attr('value');
            dish_replacement=       dish_box.attr('replacement');


            complexIngridients=ingridient.closest('.complex-ingridients');
            complexBox=ingridient.closest('.complex-box');
            complexIngridients.attr('data-type'+dish_type,dish_id);


            if(dish_replacement>0)
            {                //complexIngridients.attr('data-type'+dish_replacement,'');
               // console.log(dish_replacement);
                ingridient.siblings('.ingridient[dish_type='+dish_replacement+']').addClass('hasReplaced');
                ingridient.siblings('.ingridient[dish_type='+dish_replacement+']').attr('replacedBy',dish_type);
                ingridient.attr('dish_replacement',dish_replacement);
            }
            else
            {
                //console.log(ingridient_box.find('.ingridient'));
                ingridient_box.find('.hasReplaced').each(function()
                {
                    if(dish_type==$(this).attr('replacedBy'))
                    {
                        $(this).removeClass('hasReplaced');
                        typeId=$(this).attr('dish_type');
                        replaced_dish_id=$(this).attr('dish_id');
                        complexIngridients.attr('data-type'+typeId,replaced_dish_id);
                    }

                });
            }

            ingridient.find('.dish_name').text(dish_name);
            ingridient.attr('dish_name',dish_name);
            lineHeight=parseInt(ingridient.find('.dish_name').css('line-height'),10);
            blockHeight=ingridient.find('.dish_name').height();
            lineCount=Math.round(blockHeight/lineHeight);

            marginTop=lineHeight*(lineCount-1)+15;
            ingridient.find('.weight').html('<div class="choose">'+dish_weight + '</div>');


            if(dish_image)
            {
                ingridient.find('.dish_image').find('svg').hide();
                ingridient.find('.dish_image').find('img').remove();
                ingridient.find('.dish_image').append('<img ' +
                    'style="\n' +
                    '    width: 100%;\n' +
                    '    margin:  0;\n' +
                    '    padding:  0;\n' +
                    // '    margin-top: -'+ marginTop +'px;\n' +
                    // '    margin-bottom: -28px;\n' +
                    '"src="/assets/images/uploads/dishes/'+dish_image+'">');
                ingridient.find('.dish_name').css('margin-bottom','');
                ingridient.find('.dish_image').css('margin-left',0);
            }
            else
            {
                ingridient.find('.dish_name').css('margin-bottom','-15px');
                ingridient.find('.dish_image').find('svg').show();
                ingridient.find('.dish_image').find('img').remove();
                ingridient.find('.dish_image').css('margin-left','');
            }
            ingridient.attr('dish_id',dish_id);

            ingridient.addClass('choosen');



            ingTotal=complexIngridients.find('.ingridient').length;
            ingChosen=complexIngridients.find('.ingridient.choosen').length;
            ingRepl=complexIngridients.find('.ingridient.hasReplaced').length;



            countChose=ingTotal-ingChosen-ingRepl;
            if(countChose<=0)
            {
                complexBox.find('.btnAddComplex').removeClass('inactive');
                console.log(countChose,'ingTotal-ingChosen+ingRepl');
                complexBox.find('.complexChooseText').hide();
                complexBox.find('.complexBuyCount ').css('display','table');
            }
            else
            {
                complexBox.find('.complexChooseText').show();
                complexBox.find('.complexBuyCount ').hide();
                complexBox.find('.btnAddComplex').removeClass('inactive');
                complexBox.find('.btnAddComplex').addClass('inactive');
                console.log(countChose,'2222');
            }
            //ingridient.css('background-color','rgb(221, 255, 222)');
           // ingridient.css('color','rgb(1, 93, 11)');
            $('#myModal').modal('hide');
        });

        $('body').on('click','.ingridient',function()
            {

                if(!$(this).hasClass('alreadyModal') && !$(this).hasClass('hasReplaced')) {
                    $('.ingridient').addClass('alreadyModal');
                    ingridient = $(this);
                    if (!ingridient.hasClass('active')) {
                        $(this).addClass('active');
                        complex_id = $(this).attr('complex_id');
                        dish_type_id = $(this).attr('dish_type');
                        dish_name = $(this).attr('dish_name');

                        //console.log(dish_type_id);
                        $.post('/site/one-dish-type', {
                            '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->getCsrfToken()?>',
                            complex_id: complex_id,
                            dish_type_id: dish_type_id,
                            dish_name: dish_name
                        }).done(function (data) {
                            $('#myModal').remove();
                            $('.container').append(data);
                            $('#myModal').modal('show');
                            $('#myModal').on('hidden.bs.modal', function () {
                                $
                                ingridient.removeClass('active');
                            });
                            // console.log(data);
                        });
                    }
                }
            });



    </script>