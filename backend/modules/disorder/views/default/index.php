<?php
//print_r(count($arDishTypes));
//print_r($arDishTypes);
//
//foreach($arDishTypes as $key=>$dishType)
//{
//
//    foreach ($dishType['dishes'] as $dishKey => $dish)
//    {
//
//
//        foreach ($dishType['dishCost'] as $dishCostKey => $dishCost)
//        {
//            $flagDay=0;
//
//            if($dish['id']==$dishCost['dish_id'])
//            {
////                echo $dish['id'];
//                $arDishTypes[$key]['dishes'][$dishKey]['cost'][]=$dishCost;
//                break;
//            }
//        }
//
//        foreach ($dishType['dishDays'] as $dishDaysKey => $dishDay)
//        {
//            if($dish['id']==$dishDay['dish_id'])
//            {
//                $flagDay=1;
////                echo $dish['id'];
//                $arDishTypes[$key]['dishes'][$dishKey]['days'][]=$dishDay;
//            }
//
//        }
//        if( $flagDay==0)
//        {
//            unset($arDishTypes[$key]['dishes'][$dishKey]);
//        }
//
//    }
//
//}

use yii\jui\DatePicker;
use backend\modules\disorder\assets\DisorderAsset;
DisorderAsset::register($this);
//print_r($arBuffets);

echo '<div class="datePickerBlock">';
echo DatePicker::widget([
    'options'  =>['tableType'=>$tableType] ,
    'name'  => 'from_date',
    'value'  => $date,
    'language' => 'ru',
    'dateFormat' => 'yyyy-MM-dd',
]);
echo '</div>';

if(!$flagReturn)
{

    $flagReturn=0;
}
else
{
    $tableClass='flagReturn';
}

?>

<?php
if($returnBufet)
{

    echo $this->render('_differenceTable',[
        'tableType'=>$tableType,
        'date'=>$date,
        'arBuffets'=>$arBuffets,
        'arDishes'=>$arDishes,

    ]);
}
else
{
    echo $this->render('_mainTable',[
            'tableClass'=>$tableClass,
            'tableType'=>$tableType,
            'date'=>$date,
            'arBuffets'=>$arBuffets,
            'arDishes'=>$arDishes,
            'flagReturn'=>$flagReturn,
    ]);
}