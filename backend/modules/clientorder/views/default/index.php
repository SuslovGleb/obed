<?php

use yii\bootstrap\Collapse;

use backend\modules\clientorder\assets\ClientorderAsset;
use backend\models\NumberColumn;
use \kartik\grid\GridView;
use \yii\jui\DatePicker;
use \yii\widgets\Pjax;
//echo $viewPAth;
ClientorderAsset::register($this);

if (!$date) {
    $vDate = date('Y-m-d');

} else {
    $vDate = date('Y-m-d', strtotime($date));
}
//echo '<pre>'.print_r(\backend\models\OrderInfo::find()->OrderByTelephoneIdAndDateSUM(78,$date)).'</pre>';
echo '<div class="datePickerOrders">';
echo DatePicker::widget(
    [
        'name'       => 'from_date',
        'value'      => $vDate,
        'language'   => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]);
echo '</div>';
$allSum=0;
$driverSum=[];
?>

<a href="/admin/clientorder/?date=<?= $vDate ?>" class="btn btn-<?= $date ? 'info' : 'primary'; ?>">За день</a>
<a href="/admin/clientorder/" class="btn btn-<?= $date ? 'primary' : 'info'; ?>">За все время</a>
<?php Pjax::begin(['id' => 'pjax_1']); ?>

<?= GridView::widget(
    [
        'dataProvider' => $dataProvider,

        'rowOptions' => function ($model, $key, $index, $grid) {
            $lastOrderTime = $model->delivery_time;

            $days = (strtotime(date("Y-m-d")) - strtotime(date("Y-m-d", strtotime($lastOrderTime)))) / (60 * 60 * 24);

            if ($days < 7 && $days > 1) {
                $rowClass = 'week';
            } else if ($days <= 1) {
                $rowClass = 'day';
            } else if ($days > 7) {
                $rowClass = 'moreWeek';
            }
            return [
                'client-id'    => $model->telephone->client_id,
                'address-id'   => $model->address->id,
                'telephone-id' => $model->telephone->id,
                'firm-id'      => $model->firm->id,
                'class'        => $rowClass . ' clientRow',
            ];

        },
        //    'rowOptions' => [
        //            'id' => $models[$i]['id'],$model->address->address->address
        //        $addressId=$order->address->id;
        //        $telephone=$order->telephone->client_telephone;
        //        $clientId=$order->telephone->client_id;
        //            'client-id'=>
        //            'address-id'=>
        //            'telephone-id'=>
        //            'firm-id'=>
        //            ],
        'columns'    => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',

            [
                'class'         => 'kartik\grid\ExpandRowColumn',
                'width'         => '50px',
                'value'         => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
                'detail'        => function ($model, $key, $index, $column) use ($date,$allSum,$driverSum) {
                    if (!$date) {
                        $modelOrderInfo = \backend\models\OrderInfo::find()->OrderByTelephoneId($model->telephone_id);
                    } else {
                        $modelOrderInfo = \backend\models\OrderInfo::find()->OrderByTelephoneIdAndDate($model->telephone_id, $date);
                    }
                    $orderTables=\backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrderInfo,true);
                    $model->sum=$orderTables['allSum'];

//                    $model->sum=$allSum;
                    return $orderTables['tables'];
//                    return Yii::$app->controller->renderPartial(
//                        'print', [
//                        'modelOrderInfo' => $modelOrderInfo,
//                        'showOnlyTable'  => true,
//                    ]);
                },
                'headerOptions' => ['class' => 'kartik-sheet-style'],
                'expandOneOnly' => true,
            ],


//[
//            'class' => 'kartik\grid\EditableColumn',
//            'attribute' => 'address.address.address',
//            'editableOptions' => function ($model, $key, $index) {
//                return [
//                    'header' => ' ',
//                    'placement' => 'left',
//                    'inputType' => \kartik\editable\Editable::INPUT_TEXTAREA,
//                    'formOptions' => [
//                        'action' => ['/admin/config-google-analytics/update-fast'],
//                    ]
//                ];
//            },
//        ],

            'address.address.address',
            'firm.name',
            //        'delivery_time',
            //        'telephone.client_telephone',

            [
                'attribute' => 'Telephone',
                'format'    => 'raw',
                'value'     => function ($data) {
                    $telephone = strrev($data->telephone->client_telephone);

                    if ($telephone[1] == 9) {
                        $pattern = '/(7)(9..)(.*)/i';
                        $replacement = '<span class="telF">+$1</span><span class="telB">(${2})-$3</span>';
                        $telephone = preg_replace($pattern, $replacement, $telephone);
                        $telephone = $telephone;
                    } else if ($telephone[1] == 4) {
                        $replacement = '<span class="telB">$3-$4-$5</span>';
                        $pattern = '/(7)(4...)(..)(..)(..)/i';
                        $telephone = preg_replace($pattern, $replacement, $telephone);
                        $telephone = $telephone;
                    } else {
                        $replacement = '<span class="telE">$1$2</span>';
                        $pattern = '/(7)(.*)/i';
                        $telephone = preg_replace($pattern, $replacement, $telephone);
                        $telephone = $telephone;
                    }

                    return $telephone;
                },
            ],
            [
                'class' => NumberColumn::className(),
                'attribute' => 'sum',
                'enableSorting' => true,
//                'asc' => ['sum' => SORT_ASC],
//                'desc' => ['sum' => SORT_DESC],
//                'default' => SORT_DESC
            ],
            'driver.worker_name',
            ['class' => 'yii\grid\ActionColumn'],

        ],
        'showFooter' => true,

    ]); ?>

<?php Pjax::end(); ?>
<?php
//print_r($driverSum);
//$date = '2018-03-15';
//$date = strtotime($date);
//$m = date("m", $date);
//$d = date("d", $date);
//$Y = date("Y", $date);
//$date = date("Y-m-d", $date);
//$nextDay = mktime(0, 0, 0, $m, $d + 1, $Y);
//$nextDay = date("Y-m-d", $nextDay);
//echo '<a href="/admin/clientorder/default/print" class="btn btn-primary flagReturn" flagreturn="1">Распечатать все</a>';
//print_r($date);
//print_r($nextDay);
//print_r(date("Y-m-d", '2018-03-18'));
$allSum = 0;


foreach ($modelOrderInfo as $key => $order) {
//    echo '<tr>';
    $totalSum = 0;

//        print_r($order->getAttributes());
//        print_r($order->attributes->telephone);

//    $address = $order->address->address->attributes['address'];
//    $telephone = '+' . strrev($order->telephone->attributes['client_telephone']);


    $id = $order->attributes['id'];
    $firm = $order->firm->attributes['name'];
    $order_time = $order->attributes['order_time'];
    $delivery_time = $order->attributes['delivery_time'];
    $driver = $order->driver->attributes['worker_name'];
    $comment = $order->attributes['comment'];
    $status = $order->status0->attributes['status_name'];
    $operator = $order->operator->attributes['username'];
    $operatorEmail = $order->operator->attributes['email'];

//    $comment ? $comment = "($comment)" : '';
//
//
//    $items[$key]['content'] = '<table class="order_table">
//            <tbody>
//            <tr>
//                <th>№</th>
//                <th style="width: 241px;">Наименование</th>
//                <th>Цена,руб</th>
//                <th>Количество</th>
//                <th>Сумма,руб</th>
//            </tr>';
//    $items[$key]['contentOptions'] = ['class' => 'out'];

    foreach ($order['orderMains'] as $orderKey => $oneOrder) {
        $total = 0;
        $quantity = $oneOrder['suborder']['quantity'];
        $orders[$key][$orderKey]['cost'] = $orders[$key][$orderKey]['cost'] ? 0 : $orders[$key][$orderKey]['cost'];
//        $orders[$key][$orderKey]['quantity'] = $quantity;

        if ($oneOrder['suborder']['dish_id']) {
            $dish_cost = $oneOrder['suborder']['dish']['dishCost']['cost'];
//            $dish_name = $oneOrder['suborder']['dish']['name'];
//            $dish_id = $oneOrder['suborder']['dish']['id'];
//
//            $orders[$key][$orderKey]['cost'] = $dish_cost;
//            $orders[$key][$orderKey]['name'] = $dish_name;
//
//            $additional_dish_name = '';
//            $additional_dish_id = '';
            if ($oneOrder['suborder']['additional_dish_id']) {
//                $additional_dish_name = $oneOrder['suborder']['additionalDish']['name'];
//                $additional_dish_id = $oneOrder['suborder']['additionalDish']['id'];
                $additional_dish_cost = $oneOrder['suborder']['additionalDish']['dishCost']['cost'];
//
                $orders[$key][$orderKey]['cost'] .= '+' . $additional_dish_cost;
//                $orders[$key][$orderKey]['name'] = $dish_name . '+' . $additional_dish_name;

                $total += $additional_dish_cost;
            }

            $total += $dish_cost;
            $total = $total * $quantity;
//            $orders[$key][$orderKey]['total'] = $total;
        }

        if ($oneOrder['suborder']['complex_id']) {
//            $complex_name = $oneOrder['suborder']['complex']['name'];
//            $complex_id = $oneOrder['suborder']['complex']['id'];
            $complex_price = $oneOrder['suborder']['complex']['price'];
//
//            $orders[$key][$orderKey]['name'] .= ' ) ';
//            $orders[$key][$orderKey]['cost'] = $complex_price;
//            $orders[$key][$orderKey]['name'] = $complex_name;
//
//            $complex_suborder = $oneOrder['suborder']['orderSuborderComplex'];
//            $orders[$key][$orderKey]['name'] .= ' ( ';
//            foreach ($complex_suborder as $suborderKey => $complexDish) {
//                $complex_dish_id = $complexDish['dish']['id'];
//                $complex_dish_name = $complexDish['dish']['name'];
//                $orders[$key][$orderKey]['name'] .= $complex_dish_name;
//                if ($suborderKey < count($complex_suborder) - 1) {
//                    $orders[$key][$orderKey]['name'] .= '+';
//                }
//            }
            $total += $complex_price * $quantity;
//            $orders[$key][$orderKey]['total'] = $total;


        }
//        $num = $orderKey + 1;
//        $items[$key]['content'] .= '
//                <tr>
//                <td>' . $num . '</td>
//                <td>' . $orders[$key][$orderKey]['name'] . '</td>
//                <td>' . $orders[$key][$orderKey]['cost'] . '</td>
//                <td>' . $quantity . '</td>
//                <td>' . $total . '</td>
//
//                </tr>';
        $totalSum += $total;
    }
//    $items[$key]['content'] .= '
//            </tbody>
//        </table>
//        <div class="orderTotalSum" style="display: block;">' . $totalSum . '</div>';
//    $items[$key]['label'] = $key + 1 . ',' . $address . ',' . $telephone . ',' . $firm . ','
////        . $order_time . ','
////        . $delivery_time . ','
//
//        . $comment . ','
////        . $status . ','
////        . $operator . ','
////        . $operatorEmail . ','
//        . $totalSum . ',' . $driver;
    $driverSum[$driver] += $totalSum;
//    $orders[$key]['totalSum'] = $totalSum;
//    $allSum += $totalSum;
}
?>

<?php
foreach ($driverSum as $driver => $sum) {
    echo $driver . ': ' . $sum . '</br>';
}
//echo 'ИТОГО: ' . $allSum;
//echo Collapse::widget(
//    [
//        'items' => $items,
//    ]);

?>
<!--<pre>-->
<!--    --><?php
//    print_r($orders);
//    ?>
<!--</pre>-->
<!--<pre>-->
<!--    --><?php
//    print_r($modelOrderInfo);
//    ?>
<!--</pre>-->