<?php
//print_r($modelOrders)
//$model = $dataProvider->getModels();
\yii\widgets\Pjax::begin(
    [
        'id'              => 'pjax-students-gridview',
        'timeout'         => false,
        'enablePushState' => false,
    ]);
?>

<div class="btn btn-info btnAddClientToggle"><i class="glyphicon glyphicon-chevron-down"></i> Добавить Клиента</div>
<?= $this->render('_addClientInputs',[]);?>

<?= \yii\grid\GridView::widget(
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
                'contentOptions' => ['class' => 'address'],
                'attribute'      => 'address.address.address',
                'value'          => function ($data) {
//        if($data->id==533)                    print_r($data->address);
                    if($data->address->comment)
                    {
                        $adr=$data->address->address->address.', '.$data->address->comment;
                    }
                    else
                    {
                        $adr=$data->address->address->address;
                    }
                    return $adr;
                    },
            ],
            [
                'contentOptions' => ['class' => 'firm'],
                'attribute'      => 'firm.name',
            ],

            //        'delivery_time',
            //        'telephone.client_telephone',

            [
                'contentOptions' => ['class' => 'telephone'],
                'attribute'      => 'Telephone',
                'format'         => 'raw',
                'value'          => function ($data) {
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


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
\yii\widgets\Pjax::end(); ?>
<!--<table class="clients">-->
<!--    <tr>-->
<!--        <th>№</th>-->
<!--        <th>Фирма</th>-->
<!--        <th>Адрес</th>-->
<!--        <th>Телефон</th>-->
<!---->
<!--        <th>Последний заказ(дней)</th>-->
<!--    </tr>-->
<!--    -->
<?php
////    print_r($modelOrders);
//    foreach ($modelOrders as $key=>$order) {
//        $key++;
//        $address=$order->address->address->address;
//        $addressId=$order->address->id;
//        $telephone=$order->telephone->client_telephone;
//        $clientId=$order->telephone->client_id;
////        $telephone = '+' . strrev($order->telephone->attributes['client_telephone']);
//        $telephone = strrev($order->telephone->attributes['client_telephone']);
//
//
//
//
//
//        if($telephone[1]==9)
//        {
//            $pattern = '/(7)(9..)(.*)/i';
//            $replacement = '<span class="telF">+$1</span><span class="telB">(${2})-$3</span>';
//            $telephone=preg_replace($pattern, $replacement, $telephone);
//            $telephone = $telephone;
//        }
//       else if($telephone[1]==4)
//       {
//           $replacement = '<span class="telB">$3-$4-$5</span>';
//           $pattern = '/(7)(4...)(..)(..)(..)/i';
//           $telephone=preg_replace($pattern, $replacement, $telephone);
//           $telephone = $telephone;
//       }
//       else
//       {
//           $replacement = '<span class="telE">$1$2</span>';
//           $pattern = '/(7)(.*)/i';
//           $telephone=preg_replace($pattern, $replacement, $telephone);
//           $telephone = $telephone;
//       }
//
//
//        $telephoneId=$order->telephone->id;
//        $firm=$order->firm->name;
//        $firmId=$order->firm->id;
//        $lastOrderTime=$order->delivery_time;
//
//
//        $days=(strtotime(date("Y-m-d"))-strtotime(date("Y-m-d", strtotime($lastOrderTime))))/(60*60*24);
////
////            $date->setTimestamp($lastOrderTime);
////            $now = date();
////            $diff = $now->diff($date, true);
////            $days = $diff->d;
//
//        if($days<7&&$days>1)
//        {
//            $trIndicator='week';
//        }
//        else if($days<=1)
//        {
//            $trIndicator='day';
//        }
//        else if($days>7)
//        {
//            $trIndicator='moreWeek';
//        }
//        ?>
<!--        <tr class="--><? //=$trIndicator?><!-- clientRow" client-id="--><? //=$clientId?><!--"address-id="--><? //=$addressId?><!--" telephone-id="--><? //=$telephoneId?><!--" firm-id="--><? //=$firmId?><!--">-->
<!--            <td>--><? //=$key?><!--</td>-->
<!--            <td class="firm" >--><? //=$firm?><!--</td>-->
<!--            <td class="addres" >--><? //=$address?><!--</td>-->
<!--            <td class="telephone" >--><? //=$telephone?><!--</td>-->
<!---->
<!--            <td>--><? //=$lastOrderTime?><!--</td>-->
<!--        </tr>-->
<!--        --><?php
//    }
//    ?>
<!--</table>-->