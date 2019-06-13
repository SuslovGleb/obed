<?php
use common\widgets\DishesWidget;
use common\widgets\DishesTypeWidget;
use backend\models\ComplexModal;
use yii\bootstrap\Nav;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<?php

$arDishes=$dishes->join;
$arCounts=$dishes->from;

$arDishes=$arDishes[0][1]['T2'];
$arCounts=$arCounts['T1'];
//echo '<pre>';
//
//print_r($arCounts);
//echo '</pre>';
//print_r($command->params);
//
//// возвращает все строки запроса
//$rows = $command->queryAll();

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



<?php
            foreach ($dishTypes as $Types)
            {
                $typeBtnsList[]=
                [
                    'label' => $Types['name'],
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


                foreach ($arDishes as $dish)
                {

//                    echo '<pre>';
//
//                    print_r($dish['bcount']);
//                    echo '</pre>';
                   // print_r($Types['typeId']);
                   // print_r($dish['type_Id']);
                    if($Types['typeId']==$dish['type_Id'])
                    {
                        $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidget::widget(['arCounts'=>$arCounts,'dishes'=>$arDishes,'type'=>$Types['typeId']]);
                        break 1;
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
<!--<script>-->
<!--    function sesionDishId(elem)-->
<!--    {-->
<!--        $.post("index.php?r=site/ajax-ses",{DishId: $(elem).attr('dish_id')  }).done(function(data){alert(data);});-->
<!--    }-->
<!--    //$.post("ajaxSessionDishId.php",{DishId:1}).done(function(data){alert(data);}); -->
<!--    </script>-->