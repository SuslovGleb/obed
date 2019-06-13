<?php
use common\widgets\DishesWidget;
use common\widgets\DishesWidgetNew;
use common\widgets\DishesTypeWidget;
use frontend\models\ComplexModal;
use yii\bootstrap\Nav;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use backend\controllers\Svg;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';

?>
<?php


Pjax::begin(['id' => 'pjaxComplexDish',
    //'enablePushState' => false,
   // 'linkSelector' => '.dishTypeLink'
]);

    if($complexId)
    {
       $modalOptions=[
            'complexImage'=>$complexImage,
            'id' => 'userModal'.$complexId,
            'header' => '<div class="complex_modal_name">'.$complexName.'</div>',
            'size' => 'modal-lg',
            'clientOptions'=>
                [
                    'show' => true,
                ]
            ];

        ComplexModal::begin($modalOptions);
    }
?>



<?
$svg=new Svg;
$small_images['1']=$svg->Salat('#FFFFFF',45);
$small_images['2']=$svg->Sup('#FFFFFF',45);
$small_images['3']=$svg->Meet('#FFFFFF',45);
$small_images['4']=$svg->Garnir('#FFFFFF',45);
$small_images['5']=$svg->Vipechka('#FFFFFF',45);

$Meet   =$small_images['3'];
$Garnir =$small_images['4'];

            foreach ($dishTypes as $Types)
            {
                $typeBtnsList[]=
                [
                    'label' => $Types['name'].'<br>'.$small_images[$Types['typeId']] ,
                    'encode' => false,
                   // 'url' => Url::toRoute(
//                        [
//                            'dishes',
//                            'dishType' =>$Types['typeId'],
//                            'complexId'=>$complexId,
//                            'complexName'=>$complexName,
//                            'complexImage'=>$complexImage,
//                        ]),
                    //'options'=>['class' =>'dishTypeBtn'],
                    'linkOptions' => ['class' =>'dishTypeLink'],

                ];

               if($Types['typeId']==$dishType)
               {
                  $typeBtnsList[ count($typeBtnsList)-1 ]['active'] = true;
               }

//                foreach($dishes['dishesNotInOrder'] as $dish)
//                {
//                    $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidgetNew::widget(['order'=>true, 'dish'=>$dish,'type'=>$Types['typeId'],'Meet'=>$Meet, 'Garnir'=>$Garnir]);
//                }
                foreach($dishes['dishesInOrder'] as $dish)
                {
                    if($Types['typeId']==$dish['dishType']['id'])
                    {
                    $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidgetNew::widget(['order'=>true, 'dish'=>$dish,'type'=>$Types['typeId'],'Meet'=>$Meet, 'Garnir'=>$Garnir]);
                    }
                }
                foreach($dishes['dishesNotInOrder'] as $dish)
                {
                    if($Types['typeId']==$dish['dishType']['id'])
                    {
                    $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidgetNew::widget(['order'=>false, 'dish'=>$dish,'type'=>$Types['typeId'],'Meet'=>$Meet, 'Garnir'=>$Garnir]);
                    }
                }

            }
            echo Tabs::widget([
            'items' => $typeBtnsList,
            'options' => [
                'class'=>'nav-tab',
                'style'=>[
//                    'width'=>count($dishTypes)*130 . 'px',
                    'margin'=>'0 auto',
                    'background-color'=>'#1b3e00b5',

                     'text-shadow'=> '2px 2px 2px black',

                ],
                
                ], // set this to nav-tab to get tab-styled navigation
            ]);
            ?>

<?php 
    if($complexId)
    {
        ComplexModal::end();
    }

Pjax::end();

?>
<script>




    $('body').on("click",".btnAddDish",function(){
        if($(this).hasClass('btnmodal'))
        {
            additional_dish_cost=   $(this).closest('.dish_box').attr('cost');
            additional_dish_weight= $(this).closest('.dish_box').attr('weight');
            additional_dish_id=     $(this).closest('.dish_box').attr('dish_id');
            additional_dish_name=   $(this).closest('.dish_box').attr('value');
            dish_id=                $(this).closest('.modal-content').find('.modal-header').find('.complex_modal_name').attr('dish_id');

            dish_box=               $('.dish_box[dish_id="'+dish_id+'"]');
            dish_box_cost=          dish_box.find('.costNum');


            dish_box.find('.dish_name').text(dish_box.attr('value')+' + '+additional_dish_name);

            dish_box.attr('additional_dish_id',additional_dish_id);
            dish_box.attr('additional_dish_name',additional_dish_name);
            dish_box.attr('additional_dish_weight',additional_dish_weight);
            dish_box.attr('additional_dish_cost',additional_dish_cost);


            dish_box_cost.text(parseInt(dish_box_cost.text())+parseInt(additional_dish_cost));

            clone_box=dish_box.find('.add_additional_dish');
            new_clone_box=clone_box.clone();

            clone_box.addClass('glyphicon-remove').removeClass('glyphicon-plus');
            clone_box.after(new_clone_box);
            new_clone_box.addClass('glyphicon-pencil').removeClass('glyphicon-plus');

            dish_box.find('.dish_image').after('<div class="additional_image glyphicon glyphicon-plus"></div>');

            $('#myModal').modal('hide');
            // <div class="additional_image glyphicon glyphicon-plus"></div>
        }
        else {
            count = $(this).siblings('.dishBuyCount').find('.dishInputNum').val();

            dish_box = $(this).closest(".dish_box");

            dish_id = dish_box.attr('dish_id');
            dish_name = dish_box.attr('value');
            dish_cost = dish_box.attr('cost');
            additional_dish_id = dish_box.attr('additional_dish_id');
            additional_dish_name = dish_box.attr('additional_dish_name');
            additional_dish_cost = dish_box.attr('additional_dish_cost');

            // console.log(additional_dish_id, 'additional_dish_id');
            if (additional_dish_name) {
                final_name = dish_name + '+ <br> ' + additional_dish_name;
                final_cost_text = dish_cost + '<br>' + additional_dish_cost;
                final_cost_val = parseInt(dish_cost) + parseInt(additional_dish_cost);
                attr = ' dish_id="' + dish_id + '" additional_dish_id="' + additional_dish_id + '" ';
            }
            else {
                final_name = dish_name;
                final_cost_text = dish_cost;
                final_cost_val = dish_cost;
                attr = ' dish_id="' + dish_id + '" ';
            }

            num = $('#order_table tr').length;

            can_add_row = 1;
            $('tr:not(:first)', $('#order_table')).each(function (column, tr) {

                if (($(tr).attr('additional_dish_id') == additional_dish_id && $(tr).attr('dish_id') == dish_id)
                    ||
                    ($(tr).attr('additional_dish_id') == dish_id && $(tr).attr('dish_id') == additional_dish_id)) {
                    addDishToExist(tr);

                }
            });

            AddRowToDetailCart(can_add_row, attr, num, final_name, final_cost_text, count, final_cost_val);


        }

    });

    $('body').on('click','.add_additional_dish,.question',function()
    {
        if(!$(this).hasClass('alreadyModal')) {
            $('.add_additional_dish').addClass('alreadyModal');
            $('.question').addClass('alreadyModal');
            postType = $(this).attr('posttype');
            dish_name = $(this).closest('.dish_box').find('.dish_name').text();
            dish_id = $(this).closest('.dish_box').attr('dish_id');
            Thtml = $(this).html();
            console.log(Thtml);
            thisBlock = $(this);
            if (thisBlock.hasClass('add_additional_dish')) {
                thisBlock.removeClass('glyphicon-plus');
                thisBlock.css('background-color', '#1d8a00');
            }
            $(this).html('<div id="floatingBarsG" style="position:absolute;">\n\t<div class="blockG" id="rotateG_01"></div>\n\t<div class="blockG" id="rotateG_02"></div>\n\t<div class="blockG" id="rotateG_03"></div>\n\t<div class="blockG" id="rotateG_04"></div>\n\t<div class="blockG" id="rotateG_05"></div>\n\t<div class="blockG" id="rotateG_06"></div>\n\t<div class="blockG" id="rotateG_07"></div>\n\t<div class="blockG" id="rotateG_08"></div>\n</div>');
            $.post('/site/one-dish-type', {
                '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->getCsrfToken()?>',
                complex_id: 0,
                dish_name: dish_name,
                dish_id: dish_id,
                dish_type_id: postType,
                additionalDish: true
            }).done(function (data) {
                $('#myModal').remove();
                $('.container').append(data);
                $('#myModal').modal('show');
                if (thisBlock.hasClass('add_additional_dish')) {
                    thisBlock.addClass('glyphicon-plus');
                    thisBlock.css('background-color', '');
                }
                $('#floatingBarsG').remove();
                thisBlock.html(Thtml);
            });
        }
    });

</script>
<!--<script>-->
<!--    function sesionDishId(elem)-->
<!--    {-->
<!--        $.post("index.php?r=site/ajax-ses",{DishId: $(elem).attr('dish_id')  }).done(function(data){alert(data);});-->
<!--    }-->
<!--    //$.post("ajaxSessionDishId.php",{DishId:1}).done(function(data){alert(data);}); -->
<!--    </script>-->