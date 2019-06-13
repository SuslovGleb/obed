<?php
use common\widgets\DishesWidgetNew;
use common\widgets\DishesTypeWidget;
use frontend\models\ComplexModal;
use yii\bootstrap\Nav;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
/* @var $this yii\web\View */
//$this->title = 'My Yii Application';


    if($complexId || $additionalDishFlag)
    {
        $dish_modal_name='<h1 class="dish_modal_name" dish_id="'.$Add_dish_id.'">'.$dish_name.' + </h1>';
        $complex_modal_name='<h1 class="complex_modal_name" dish_id="'.$Add_dish_id.'">'.$dish_type_name.'</h1>';
        $header= $additionalDishFlag?$dish_modal_name:$complex_modal_name;
       $modalOptions=[
            'complexImage'=>$complexImage,
            'id' => 'myModal',
            'header' => $header,
            'size' => 'modal-lg',
            'clientOptions'=>
                [
                    'show' => true,
                ]
            ];

        ComplexModal::begin($modalOptions);
    }



                $options['type']=$dish['type'];
                $options['complexId']=$complexId;
                $options['additionalDishFlag']=$additionalDishFlag;

                foreach($dishes['dishesInOrder'] as $dish)
                {

//                    echo '<pre>';
//                    print_r($dish);
//                    echo '</pre>';
                    $options['dish']=$dish;
                    $options['order']=true;

                    if($dish['type']==$dish_type_id)
                    {
                        $typeDishesList  .=  DishesWidgetNew::widget($options);
                    }
                }
                foreach($dishes['dishesNotInOrder'] as $dish)
                {
                    $options['dish']=$dish;
                    $options['order']=false;

                    if($dish['type']==$dish_type_id)
                    {
                        $typeDishesList .=  DishesWidgetNew::widget($options);
                    }
                }

        echo $typeDishesList;
if($complexId || $additionalDishFlag) {
    ComplexModal::end();
}

?>