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
       $modalOptions=[
            'complexImage'=>$complexImage,
            'id' => 'myModal',
            'header' => '<div class="complex_modal_name" dish_id="'.$Add_dish_id.'">'.$dish_name.'</div>',
            'size' => 'modal-lg',
            'clientOptions'=>
                [
                    'show' => true,
                ]
            ];

        ComplexModal::begin($modalOptions);
    }

                if($complexId) {
                    $options['complexId']=$complexId;
                }
                if($additionalDishFlag) {
                    $options['additionalDishFlag']=true;
                }
                $options=
                    [
                        'type'=>$dish['type']
                    ];

                foreach($dishes['dishesInOrder'] as $dish)
                {

                    $options=
                        [
                            'dish'=>$dish,
                            'order'=>true
                        ];

                    if($dish['type']==$dish_type_id)
                    {

                        $typeDishesList  .=  DishesWidgetNew::widget($options);
                    }
                }
                foreach($dishes['dishesNotInOrder'] as $dish)
                {

                    $options=
                        [
                            'dish'=>$dish,
                            'order'=>false
                        ];
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