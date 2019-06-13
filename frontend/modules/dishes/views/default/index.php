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
//print_r($dishes);
//if ($this->beginCache($id, ['duration' => 3600])) {


Pjax::begin(['id' => 'pjaxComplexDish',
    //'enablePushState' => false,
    // 'linkSelector' => '.dishTypeLink'
]);

//if($complexId)
//{
//    $modalOptions=[
//        'complexImage'=>$complexImage,
//        'id' => 'userModal'.$complexId,
//        'header' => '<div class="complex_modal_name">'.$complexName.'</div>',
//        'size' => 'modal-lg',
//        'clientOptions'=>
//            [
//                'show' => true,
//            ]
//    ];
//
//    ComplexModal::begin($modalOptions);
//}
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
//echo '<pre>';
//
//print_r($dishes);
//echo '</pre>';
foreach ($dishTypes as $Types)
{
    $typeBtnsList[]=
        [
            'label' => $Types['type'].'<br>'.$small_images[$Types['type_id']] ,
            'encode' => false,
            // 'url' => Url::toRoute(
//                        [
//                            'dishes',
//                            'dishType' =>$Types['type_id'],
//                            'complexId'=>$complexId,
//                            'complexName'=>$complexName,
//                            'complexImage'=>$complexImage,
//                        ]),
        //'options'=>['class' =>'dishTypeBtn'],
        'linkOptions' => ['class' =>'dishTypeLink'],

    ];

    if($Types['type_id']==$dishType)
    {
        $typeBtnsList[ count($typeBtnsList)-1 ]['active'] = true;
    }

//                foreach($dishes['dishesNotInOrder'] as $dish)
//                {
//                    $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidgetNew::widget(['order'=>true, 'dish'=>$dish,'type'=>$Types['type_id'],'Meet'=>$Meet, 'Garnir'=>$Garnir]);
//                }

    $options['type']=$Types['type_id'];
    $options['Garnir']=$Garnir;
    $options['Meet']=$Meet;


    foreach($dishes['dishesInOrder'] as $dish)
    {



        echo '<div style="display:none;">';
        print_r($dish);
        echo '</div>';

        if($Types['type_id']==$dish['dishType']['id'])
        {
            $options['dish']=$dish;
            $options['order']=true;
            $options['additionalDishFlag']=false;

            $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidgetNew::widget($options);;

            $options['additionalDishFlag']=true;

            if($Types['type_id']==3)
            {
                $contentMeet[$dish['id']]=DishesWidgetNew::widget($options);
            }
            if($Types['type_id']==4)
            {
                $contentGarnir[$dish['id']]=DishesWidgetNew::widget($options);
            }
        }
    }
    foreach($dishes['dishesNotInOrder'] as $dish)
    {

        if($Types['type_id']==$dish['dishType']['id'])
        {
            $options['dish']=$dish;
            $options['order']=false;
            $options['additionalDishFlag']=false;


            $typeBtnsList[ count($typeBtnsList)-1 ]['content']  .=  DishesWidgetNew::widget($options);;

            $options['additionalDishFlag']=true;

            if($Types['type_id']==3)
            {
                $contentMeet[$dish['id']]=DishesWidgetNew::widget($options);
            }
            if($Types['type_id']==4)
            {
                $contentGarnir[$dish['id']]=DishesWidgetNew::widget($options);
            }
        }
    }

}
$header='<h1 class="dish_modal_name"></h1>';
$modalOptions=[
    'id' => 'dishModalMeet',
    'header' => $header,
    'size' => 'modal-lg',
    'clientOptions'=>
        [
            'show' => false,
        ]
];
ComplexModal::begin($modalOptions);
foreach ($contentMeet as $meetDish)
{
    echo $meetDish;
}
ComplexModal::end();

$header='<h1 class="dish_modal_name"></h1>';
$modalOptions=[
    'id' => 'dishModalGarnir',
    'header' => $header,
    'size' => 'modal-lg',
    'clientOptions'=>
        [
            'show' => false,
        ]
];
ComplexModal::begin($modalOptions);
foreach ($contentGarnir as $garnirDish)
{

    echo $garnirDish;
}
ComplexModal::end();

echo Tabs::widget([
    'items' => $typeBtnsList,
    'options' => [
        'class'=>'nav-tab dishesTypeNav',
        'style'=>[
//                    'width'=>count($dishTypes)*130 . 'px',
            'margin'=>'0 auto',
            'background-color'=>'rgba(162, 99, 0, 0.8)',

            'text-shadow'=> '2px 2px 2px black',

        ],

    ], // set this to nav-tab to get tab-styled navigation
]);
?>

<?php
//if($complexId)
//{
//    ComplexModal::end();
//}

Pjax::end();



   // $this->endCache();
//}