<?php

namespace backend\modules\clientorder\controllers;

use backend\models\ClientTelephones;
use backend\models\Commercial;
use backend\models\Complexes;
use backend\models\Dishes;
use backend\models\OrderInfo;
//use yii\data\ActiveDataFilter;
//use common\widgets\DishInputNum;
use backend\controllers\Svg;
use common\widgets\DishInputNumTable;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * Default controller for the `clientorder` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */


    public function actionAddOrder()
    {
        $allDishesbyDay=Dishes::find()->DishByDay('Mon');


        $complexes=Complexes::find()
            ->joinWith('complexMenus')
            ->joinWith('dishComposes')
            ->where(['active'=>1])
//            ->orderBy('price')
//            ->asArray()
            ->all();

        $types = [];
        foreach ($allDishesbyDay as $key => $Dishes) {
            $id = $Dishes->id;
            $name = $Dishes->name;
            $typeId = $Dishes->dishType->id;
            $typeName = $Dishes->dishType->type;
            $cost = $Dishes->dishCost->cost;
            $weight = $Dishes->dishCost->weight;
            $dishproducts = $Dishes->dishProducts;

            $dishComplexes=Complexes::find()
                ->select(['complexes.name','complexes.id'])
//                ->joinWith('complexMenus')
                ->joinWith('dishComposes')
                ->where(['dish_id'=>$id])
                ->andWhere(['complexes.active'=>1])
                ->all();

            $types[$typeId]['typeName'] = $typeName;
            $types[$typeId][$id]['name'] = $name;
            $types[$typeId][$id]['cost'] = $cost;
            $types[$typeId][$id]['dishComplexes'] = $dishComplexes;
            $types[$typeId][$id]['weight'] = $weight;
            foreach ($dishproducts as $prodKey => $dishproduct) {
                $types[$typeId][$id]['products'][] = $dishproduct->product->product_name;
            }
        }

        $complexShort=[];
        $complexShort['Эконом-Ланч']='ЭЛ';
        $complexShort['Эконом-Обед']='ЭО';
        $complexShort['Стандарт']='СТ';
        $complexShort['Ланч-стандарт']='ЛС';
        $complexShort['Бизнес-ланч']='БЛ';
        $complexShort['Бизнес-обед']='БО';
        $complexShort['Эконом-Ланч II']='ЭЛII';
        $complexShort['Ланч - стандарт II']='ЛСII';
        $complexShort['бизнес ланч II']='БЛII';

        return $this->render('add-order',
            [
                'types'=>$types,
                'complexShort'=>$complexShort,
                'complexes'=>$complexes,

            ]
        );
    }

    public function actionFlyer()
    {
        $allDishesbyDay['Mon']=Dishes::find()->DishByDay('Mon');
        $allDishesbyDay['Tue']=Dishes::find()->DishByDay('Tue');
        $allDishesbyDay['Wed']=Dishes::find()->DishByDay('Wed');
        $allDishesbyDay['Thu']=Dishes::find()->DishByDay('Thu');
        $allDishesbyDay['Fri']=Dishes::find()->DishByDay('Fri');
        $everyDayDish1=Dishes::find()->DishAllWeek();


        $complexes=Complexes::find()
            ->joinWith('complexMenus')
            ->joinWith('dishComposes')
            ->where(['active'=>1])
            ->orderBy('price')
//            ->asArray()
            ->all();

        return $this->render('flyer',
            [
                'everyDayDish1'=>$everyDayDish1,
                'complexes'=>$complexes,
                'allDishesbyDay'=>$allDishesbyDay,

            ]
        );
    }
    public function actionMenu()
    {
        $allDishesbyDay['Пн']=Dishes::find()->DishByDay('Mon');
        $allDishesbyDay['Вт']=Dishes::find()->DishByDay('Tue');
        $allDishesbyDay['Ср']=Dishes::find()->DishByDay('Wed');
        $allDishesbyDay['Чт']=Dishes::find()->DishByDay('Thu');
        $allDishesbyDay['Пт']=Dishes::find()->DishByDay('Fri');


        $complexes=Complexes::find()
            ->joinWith('complexMenus')
            ->joinWith('dishComposes')
            ->where(['active'=>1])
            ->orderBy('price')
//            ->asArray()
            ->all();



        $dayName = [
            'Пн' => 'Понедельник',
            'Вт' => 'Вторник',
            'Ср' => 'Среда',
            'Чт' => 'Четверг',
            'Пт' => 'Пятница',
        ];
        $svg=new Svg;
        $small_images['1']=$svg->Salat('#000',20);
        $small_images['2']=$svg->Sup('#000',15);
        $small_images['3']=$svg->Meet('#000',15);
        $small_images['4']=$svg->Garnir('#000',15);
        $small_images['5']=$svg->Vipechka('#000',15);

        return $this->render('menu',
            [
                'dayName'=>$dayName,
                'complexes'=>$complexes,
                'small_images'=>$small_images,
                'allDishesbyDay'=>$allDishesbyDay,
            ]
        );
    }
    public function orderTableFromModel($modelOrderInfo,$showOnlyTable=false,$forDetailView=false,$deleteBtn=true)
    {
        $allSum=0;






        foreach ($modelOrderInfo as $key => $order) {
            $totalSum = 0;

            $address = $order->address->address->attributes['address'];
            if ($order->comment) $comment = ' (' . $order->comment . ' )';
            $telephone = '+' . strrev($order->telephone->attributes['client_telephone']);

            $ordersIds[] = $order->attributes['id'];
            $id = $order->attributes['id'];
            $firm = $order->firm->attributes['name'];
            $firm_id = $order->attributes['firm_id'];
            $address_id = $order->attributes['address_id'];
            $order_time = $order->attributes['order_time'];
            $delivery_time = $order->attributes['delivery_time'];
            $driver = $order->driver->attributes['worker_name'];
            $driver_id = $order->attributes['driver_id'];
            $comment = $order->attributes['comment'];
            $status = $order->status0->attributes['status_name'];
            $statusId = $order->status0->attributes['id'];
            $operator = $order->operator->attributes['username'];
            $operatorEmail = $order->operator->attributes['email'];
            $telephoneId = $order->attributes['telephone_id'];

            $client=ClientTelephones::findOne($telephoneId);

            $items[$key]['id']=$id;
            $items[$key]['client_id']=$client->client_id;
            $items[$key]['statusId']=$statusId;
            $items[$key]['firm_id']=$firm_id;
            $items[$key]['address_id']=$address_id;
            $items[$key]['telephone_id']=$telephoneId;
            $items[$key]['driver_id']=$driver_id;

            $items[$key]['firm']=$firm;
            $items[$key]['address']=$address;
            $items[$key]['telephone']=$telephone;
            $items[$key]['order_time']=$order_time;
            $items[$key]['delivery_time']=$delivery_time;
            $items[$key]['driver']=$driver;
            $items[$key]['comment']=$comment;

            $items[$key]['status']=$status;
            $items[$key]['operator']=$operator;
            $items[$key]['operatorEmail']=$operatorEmail;
            $items[$key]['ordersIds']=$ordersIds;

            $comment ? $comment = "($comment)" : '';


            $items[$key]['content'] = '<table class="order_table">
            <tbody>
            <tr>
                <th>№</th>
                <th>Наименование</th>
                <th>Цена,руб</th>
                <th>Количество</th>
                <th>Сумма,руб</th>
            </tr>';
            $items[$key]['contentOptions'] = ['class' => 'out'];

            foreach ($order['orderMains'] as $orderKey => $oneOrder) {
                $total = 0;
                $quantity = $oneOrder['suborder']['quantity'];
                $orders[$key][$orderKey]['cost'] = $orders[$key][$orderKey]['cost'] ? 0 : $orders[$key][$orderKey]['cost'];
                $orders[$key][$orderKey]['quantity'] = $quantity;

                if ($oneOrder['suborder']['dish_id']) {
                    $dish_cost =
                    round($oneOrder['suborder']['dish']['dishCost']['cost']*1.25/5) * 5; //$oneOrder['suborder']['dish']['dishCost']['cost'];
                    $dish_name = $oneOrder['suborder']['dish']['name'];
                    $dish_id = $oneOrder['suborder']['dish']['id'];

                    $orders[$key][$orderKey]['cost'] = $dish_cost;
                    $orders[$key][$orderKey]['name'] = $dish_name;

                    $additional_dish_name = '';
                    $additional_dish_id = '';
                    $rowData='dish_id="'.$dish_id.'"';
                    if ($oneOrder['suborder']['additional_dish_id']) {
                        $additional_dish_name = $oneOrder['suborder']['additionalDish']['name'];
                        $additional_dish_id = $oneOrder['suborder']['additionalDish']['id'];
                        $additional_dish_cost =
                       round($oneOrder['suborder']['additionalDish']['dishCost']['cost']*1.25/5) * 5; //$oneOrder['suborder']['additionalDish']['dishCost']['cost'];

                        $orders[$key][$orderKey]['cost'] .= '+' . $additional_dish_cost;
                        $orders[$key][$orderKey]['name'] = $dish_name . '+' . $additional_dish_name;

                        $total += $additional_dish_cost;
                        $rowData.=' additional_dish_id="'.$dish_id.'"';
                    }

                    $total += $dish_cost;
                    $total = $total * $quantity;
                    $orders[$key][$orderKey]['total'] = $total;


                }

                if ($oneOrder['suborder']['complex_id']) {
                    $complex_name = $oneOrder['suborder']['complex']['name'];
                    $complex_id = $oneOrder['suborder']['complex']['id'];
                    $complex_price =round($oneOrder['suborder']['complex']['price']*1.25,-1);
                    //$complex_price = $oneOrder['suborder']['complex']['price'];

                    $orders[$key][$orderKey]['name'] .= ' ) ';
                    $orders[$key][$orderKey]['cost'] = $complex_price;
                    $orders[$key][$orderKey]['name'] = $complex_name;

                    $complex_suborder = $oneOrder['suborder']['orderSuborderComplex'];
                    $orders[$key][$orderKey]['name'] .= ' ( ';
                    foreach ($complex_suborder as $suborderKey => $complexDish) {
                        $complex_dish_id = $complexDish['dish']['id'];
                        $complex_dish_name = $complexDish['dish']['name'];

                        $orders[$key][$orderKey]['name'] .= $complex_dish_name;
                        if ($suborderKey < count($complex_suborder) - 1) {
                            $orders[$key][$orderKey]['name'] .= ' + ';
                        }
                        $dishIds[]=$complex_dish_id;
                    }
                    $orders[$key][$orderKey]['name'] .= ' ) ';
                    $total += $complex_price * $quantity;
                    $orders[$key][$orderKey]['total'] = $total;
                    $dishes=implode(",", $dishIds);
                    $rowData=' complex_attr="'.$dishes.','.$complex_id.'"';

                }
                $num = $orderKey + 1;
                if($forDetailView)
                {
                    $quant=DishInputNumTable::widget(
                        [
                            'inputOptions' => 'table-type="detailOrder"',
                            'value'        => $quantity,
                            'quantId'      => 'detailOrder'.$num
                        ]);
                }
                else
                {
                    $quant=$quantity;
                }

                $items[$key]['content'] .= '
                        <tr '.$rowData.'>
                        <td class="num">' . $num . '</td>
                        <td class="name" style="text-align: left">' . $orders[$key][$orderKey]['name'] . '</td>
                        <td class="cost" cost="' . $orders[$key][$orderKey]['cost'] . '">' . $orders[$key][$orderKey]['cost'] . '</td>
                        <td class="count">' . $quant . '</td>
                        <td class="sum"><div class="sumNum">' . $total . '</div>';
                if ($forDetailView) {
                    $items[$key]['content'] .= '<div class="glyphicon glyphicon-remove removeDishFromCart"></div>';
                }
                $items[$key]['content'] .= '</td></tr>';
                $totalSum += $total;
            }
            $items[$key]['content'] .= '
                    </tbody>
                </table>';
            $items[$key]['total'] ='    <div class="orderTotalSum" style="display: block;">ИТОГО ' . $totalSum . '</div>';

            $orders[$key]['totalSum'] = $totalSum;
            $allSum += $totalSum;
        }
        $orderTables['data']=$items;
        $modalCommercial=Commercial::find()->where(['active'=>1])->all();
        $DefaultController=new DefaultController();
//        $ththth->getViewPath() ='';
        $DefaultController->viewPath ='@backend/modules/clientorder/views/default';
        $tables=$DefaultController->renderPartial('_orderTable',
            [
                'orderTables'=>$orderTables,
                'modalCommercial'=>$modalCommercial,
                'showOnlyTable'=>$showOnlyTable,
                'forDetailView'=>$forDetailView,
                'deleteBtn'=>$deleteBtn,
            ]);
        $arReturn['tables']=$tables;
        $arReturn['data']=$items;
        $arReturn['allSum']=$allSum;

        return $arReturn;
    }
    public function actionDelete($id)
    {
        $orderInfoModel=OrderInfo::findOne($id);
        $ordersModel=OrderInfo::find()->where(
            [
                'telephone_id'=>$orderInfoModel->telephone_id,
                'address_id'=>$orderInfoModel->address_id,
                'firm_id'=>$orderInfoModel->firm->id,
                ])
            ->all();
        foreach ($ordersModel as $order)
        {
            $ids[]=$order->id;
        }

        AjaxController::actionDeleteOrderByIds($ids);
        return $this->redirect(['/clientorder/default/index']);

    }
    public function actionIndex($date='')
    {
//        $modelOrderInfo=OrderInfo::find()->AllOrders();
//        $modelOrderInfo=OrderInfo::find()->OrderById('15');
//        $modelOrderInfo=OrderInfo::find()->OrderByTelephoneId('74');
//        $modelOrderInfo=OrderInfo::find()->OrderByDate('2018-03-18 ');

        $modelOrderInfo=OrderInfo::find()->OrderByDate();
        $modelAllClients=OrderInfo::find()->AllClients($date);
        $dataProvider = new ActiveDataProvider([
            'query' => $modelAllClients,
            'pagination' => false,
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'address.address.address' => [
                    'asc' => ['address' => SORT_ASC],
                    'desc' => ['address' => SORT_DESC],
                ],
                'sum',
//                'sum'=> [
//                    'asc' => ['sum' => SORT_ASC],
//                    'desc' => ['sum' => SORT_DESC],
//                    'label' => 'sum'
//                ],
//                'firm.name',
                'firm.name' => [
                    'asc' => ['firms.name' => SORT_ASC],
                    'desc' => ['firms.name' => SORT_DESC],
                    'label' => 'firm.name'
                ]
            ]
        ]);

        return $this->render('index',
            [
//                'viewPAth'=>$this->getViewPath(),
                'dataProvider'=>$dataProvider,
                'date'=>$date,
                'modelOrderInfo'=>$modelOrderInfo,
            ]
        );
    }
    public function actionPrint($orderFind='',$id=0)
    {
        $modalCommercial=Commercial::find()->where(['active'=>1])->all();
        if($orderFind!='')
        {
            switch ($orderFind) {
                case 'byId':
                    $modelOrderInfo=OrderInfo::find()->OrderById($id);
                    break;
                case 'allButCall':
                    $modelOrderInfo=OrderInfo::find()->OrderByAceptedButCallPrint();
                    break;
            }
            $orderTables=$this->orderTableFromModel($modelOrderInfo);
            return $this->renderAjax('print',
                [
                    'showOnlyTable'=>false,
                    'orderTables'=>$orderTables,
                    'modelOrderInfo'=>$modelOrderInfo,
                    'modalCommercial'=>$modalCommercial,
                ]
            );
        }
        else
        {
            $modelOrderInfo=OrderInfo::find()->AllOrders(date('Y-m-d'));
            $orderTables=$this->orderTableFromModel($modelOrderInfo);
            return $this->render('print',
                [
                    'showOnlyTable'=>false,
                    'orderTables'=>$orderTables,
                    'id'=>$id,
                    'orderFind'=>$orderFind,
                    'modelOrderInfo'=>$modelOrderInfo,
                    'modalCommercial'=>$modalCommercial,
                ]
            );
        }

//        $modelOrderInfo=OrderInfo::find()->OrderById($id);
//        $modelOrderInfo=OrderInfo::find()->OrderById('15');
//        $modelOrderInfo=OrderInfo::find()->OrderByTelephoneId('74');
//        $modelOrderInfo=OrderInfo::find()->OrderByDate('2018-03-18 ');


    }public function actionPrintOrder($orderFind='',$id=0)
    {
        $modalCommercial=Commercial::find()->where(['active'=>1])->all();
        if($orderFind!='')
        {
            switch ($orderFind) {
                case 'byId':
                    $modelOrderInfo=OrderInfo::find()->OrderById($id);
                    break;
                case 'allButCall':
                    $modelOrderInfo=OrderInfo::find()->OrderByAceptedButCallPrint();
                    break;
            }

            $orderTables=$this->orderTableFromModel($modelOrderInfo);
            return $this->renderAjax('print-order',
                [
                    'showOnlyTable'=>false,
                    'orderTables'=>$orderTables,
                    'modelOrderInfo'=>$modelOrderInfo,
                    'modalCommercial'=>$modalCommercial,
                ]
            );
        }

    }
}
