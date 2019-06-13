<?php

namespace frontend\modules\order\controllers;

use common\models\User;
use frontend\models\FirmAddresses;
use frontend\models\Firms;
use frontend\models\FirmSite;
use frontend\models\FirmsTel;
use frontend\models\FirmEmails;
use frontend\models\FirmsSphere;
use frontend\models\FirmsTelStatus;
use frontend\models\OrderInfo;
use frontend\models\OrderMain;
use frontend\models\OrderSuborder;


use frontend\models\OrderSuborderComplex;
use frontend\models\Workers;
use yii\data\ActiveDataProvider;
use yii\web\Session;
use yii\web\Response;

use frontend\models\Spheres;
use frontend\models\Streets;
use frontend\models\ClientAddress;
use frontend\models\ClientFirm;
use frontend\models\ClientName;
use frontend\models\ClientTelephones;
use frontend\models\Clients;
use yii\web\Controller;
use Yii;

/**
 * Default controller for the `order` module
 */
class AjaxController extends Controller
{

    public function actionFindOrderById()
    {
        if (Yii::$app->request->isAjax) {
            $orderId = yii::$app->request->post('id');
            $modelOrder = OrderInfo::find()->OrderById($orderId);
            $orderTable = \backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrder, true, true, false);
            $drivers = $this->FindDrivers();
            return $this->renderPartial(
                '_orderDetailModal', [
                'orderTable' => $orderTable,
                'drivers'    => $drivers,

            ]);
        }
    }

    public function actionFindPrintOrders()
    {
        if (Yii::$app->request->isAjax) {
            $modelOrderInfo2 = OrderInfo::find()->OrderByDate();
            $modelOrderInfo = OrderInfo::find()->OrderByDatePrint(date("Y-m-d"));
            $orderActive = \backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrderInfo, true, true);
            $orderToday = \backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrderInfo2, true, true);
            return $this->renderPartial(
                'print', [
                'orderActive' => $orderActive,
                'orderToday'  => $orderToday,
            ]);
        }

    }

    public function actionFindFirmAddress()
    {
        if (Yii::$app->request->isAjax) {
            $firmId = yii::$app->request->post('firmId');
            $response = FirmAddresses::find()->byFirmId($firmId);
            return json_encode($response);
        }

    }

    public function actionFindStreets()
    {
        if (Yii::$app->request->isAjax) {
            $text = yii::$app->request->post('searchWord');
            //$response=Spheres::find()->findByText($text);
            $response1 = FirmAddresses::find()->select('id, address AS street_name')->where(
                [
                    'like',
                    'address',
                    $text
                ])->limit(20)->asArray()->all();
            $response2 = Streets::find()->where(['like', 'street_name', $text])->limit(20)->asArray()->all();
            $response = array_merge($response1, $response2);
            return json_encode($response);
        }

    }

    public function actionFindClient()
    {
        if (Yii::$app->request->isAjax) {
            $text = yii::$app->request->post('searchWord');
            //$response=Spheres::find()->findByText($text);
            $response = Clients::find()->Client($text);

            foreach ($response as $key => $data) {

                $str = strrev($data['telephone']);
                $str = '+' . substr($str, 0, 1) . '(' . substr($str, 1, 3) . ')' . substr($str, 4, 3) . '-' . substr($str, 7, 2) . '-' . substr($str, 9, 2);
                $response[$key]['telephone'] = $str;
            }


            return json_encode($response);
        }

    }

    public function actionFindSpheres()
    {
        if (Yii::$app->request->isAjax) {
            $text = yii::$app->request->post('searchWord');
            //$response=Spheres::find()->findByText($text);
            $response = Spheres::find()->where(['like', 'sphere', $text])->limit(20)->asArray()->all();
            return json_encode($response);
        }

    }

    public function actionFindFirms()
    {
        if (Yii::$app->request->isAjax) {
            $text = yii::$app->request->post('searchWord');
            //$response=Spheres::find()->findByText($text);
            $response = Firms::find()->where(['like', 'name', $text])->limit(20)->asArray()->all();
            return json_encode($response);
        }

    }

    public function actionIsAdmin()
    {
        if (Yii::$app->request->isAjax) {
            if (User::isAdmin()) {
                echo '1';
            }
        }
    }

    public function actionFindAllClients()
    {

        $subQuery = OrderInfo::find()->select(
            [
                'telephone_id',
                'max(`delivery_time`) AS MaxDate'
            ])->groupBy('telephone_id');

        $modelOrders = OrderInfo::find()->innerJoin(
            ['maxDateTable' => $subQuery], 'order_info.telephone_id = maxDateTable.telephone_id 
                  AND order_info.delivery_time = maxDateTable.MaxDate')->joinWith('telephone')->joinWith('firm')->joinWith('address')->orderBy(['address' => SORT_ASC])->groupBy(['telephone_id']);

        $dataProvider = new ActiveDataProvider(
            [
                'query'      => $modelOrders,
                'pagination' => false,
            ]);
        $dataProvider->setSort(
            [
                'attributes' => [
                    'id',
                    'Address',
                    'firm.name' => [
                        'asc'   => ['firm.name' => SORT_ASC],
                        'desc'  => ['firm.name' => SORT_DESC],
                        'label' => 'firm.name'
                    ]
                ]
            ]);
        $dataProvider->pagination = false; // отключаем пагинацию


        $model = $modelOrders;

        $modelOrders->all();
        return $this->renderAjax(
            '_allClients', [
            'model'        => $model,
            'dataProvider' => $dataProvider,
            'modelOrders'  => $modelOrders,
        ]);
    }

    public function actionAceptOrderById()
    {
        if (Yii::$app->request->isAjax) {
            $id = yii::$app->request->post('id');
            $order = OrderInfo::findOne($id);
            $order->status = 2;
            if (!$order->save()) print_r($order->getErrors());
        }
    }

    public function actionDeleteOrderByIds()
    {
        if (Yii::$app->request->isAjax) {
            $arId = yii::$app->request->post('arId');
            if ($arId) {
                if (is_array($arId)) {

                    foreach ($arId as $id) {
                        $orderInfo = OrderInfo::findOne($id);
                        $orderMain = OrderMain::find()->where(['order_info_id' => $id])->all();
                        foreach ($orderMain as $key => $val) {
                            $suborder = OrderSuborder::findOne($val->suborder_id);

                            $suborder->delete();
                            $val->delete();
                            OrderSuborderComplex::deleteAll(['composition_id' => $suborder->complex_composition]);
                        }
                        $orderInfo->delete();
                    }
                } else {
                    $id = $arId;
                    $orderInfo = OrderInfo::findOne($id);
                    $orderMain = OrderMain::find()->where(['order_info_id' => $id])->all();
                    foreach ($orderMain as $key => $val) {
                        $suborder = OrderSuborder::findOne($val->suborder_id);

                        $suborder->delete();
                        $val->delete();
                        OrderSuborderComplex::deleteAll(['composition_id' => $suborder->complex_composition]);
                    }
                    $orderInfo->delete();
                }
            }


        }
    }

    public function actionFindSameOrder()
    {
        if (Yii::$app->request->isAjax) {

            $deliveryTime = str_replace(" ", "", yii::$app->request->post('deliveryTime'));
            $deliveryTime = date("Y-m-d") . " " . strftime("%R", strtotime($deliveryTime));

            $addrId = yii::$app->request->post('addrId');
            $telId = yii::$app->request->post('telId');
            $firmId = yii::$app->request->post('firmId');

            $orderConfirm = OrderInfo::find()->SameOrder($addrId, $telId, $firmId, $deliveryTime);

            if (count($orderConfirm)) {
                echo '1';
            } else {
                echo '0';
            }
        }
    }

    public function actionOrderConfirm()
    {
        if (Yii::$app->request->isAjax) {

            $session = Yii::$app->session;

            $deliveryTime = str_replace(" ", "", yii::$app->request->post('deliveryTime'));
            $driver_id = yii::$app->request->post('driver_id');
            $newOrder = yii::$app->request->post('newOrder');
            $comment = yii::$app->request->post('comment');
            $order = yii::$app->request->post('order');
            $clientOrder = yii::$app->request->post('clientsOrder') == '1' ? true : false;


            $newOrder == 'true' ? $newOrder = 1 : $newOrder = 0;
            $orderTime = date("Y-m-d H:i:s");
            $deliveryTime = date("Y-m-d") . " " . strftime("%R", strtotime($deliveryTime));

//            $driver_id = $session->get('driver_id');


            if ($clientOrder) {

                $client_name = yii::$app->request->post('clientName');
                $addresses = yii::$app->request->post('address_name');
                $telephones = yii::$app->request->post('telephone_text');
                $client_firm = yii::$app->request->post('firmName');


                $data = [];
                $data['telephones'] = $telephones;
                $data['addresses'] = $addresses;
                $data['adrComment'] = '';
                $data['client_name'] = $client_name;
                $data['client_firm'] = $client_firm;
                $data['firm_sphere'] = '';

                $returnData = $this->AddClient($data, true);


                $addrId = $returnData['addrId'];
                $telId = $returnData['client_tel_id'];
                $client_id = $returnData['client_id'];
                $firmId = $returnData['firm_id'];


            } else {
                $addrId = yii::$app->request->post('addrId');
                $telId = yii::$app->request->post('telId');
                $client_id = $session->get('client_id');
                $firmId = yii::$app->request->post('firmId');


                if (!$addrId) {
                    $address = new FirmAddresses();
                    $address->address = yii::$app->request->post('address_name');
                    $address->firm_id = yii::$app->request->post('firmId');
                    $address->save();
                    $addrId = Yii::$app->db->lastInsertID;

                    $clAddress = new ClientAddress();
                    $clAddress->address_id = $addrId;
                    $clAddress->cilent_id = $client_id;
                    $clAddress->adding_date = date("Y-m-d");
                    $clAddress->save();
                    $addrId = Yii::$app->db->lastInsertID;
                }
                if (!$telId) {
                    $client_telephone = preg_replace("/[^0-9]/", '', yii::$app->request->post('telephone_text'));
                    $client_telephone = strrev($client_telephone);

                    $clientTel = new ClientTelephones();
                    $clientTel->adding_date = date("Y-m-d");
                    $clientTel->client_telephone = $client_telephone;
                    $clientTel->client_id = $client_id;
                    $clientTel->save();


                    $telId = Yii::$app->db->lastInsertID;
                }
            }


            $arData['firmId'] = $firmId;
            $arData['comment'] = $comment;
            $arData['addrId'] = $addrId;
            $arData['deliveryTime'] = $deliveryTime;
            $arData['driver_id'] = $driver_id;
            $arData['orderTime'] = $orderTime;
            $arData['clientOrder'] = $clientOrder;
            $arData['telId'] = $telId;
            $arData['order'] = $order;


            $this->InsertOrderInfo($newOrder, $arData);
//
//            $orderConfirm->firm_id = $firmId;
//            $orderConfirm->comment = $comment;
//            $orderConfirm->address_id = $addrId;
//            $orderConfirm->delivery_time = $deliveryTime;
//            $orderConfirm->driver_id = $driver_id;
//            $orderConfirm->order_time = $orderTime;
//            $orderConfirm->status = $status;
//            $orderConfirm->telephone_id = $telId;
//            if (!$clientOrder) {
//                $orderConfirm->operator_id = $operatorId;
//            }
////        $orderConfirm->save();
//
//            if (!$orderConfirm->save()) print_r($orderConfirm->getErrors());
//
////
//            $order_id = Yii::$app->db->lastInsertID;
//            echo '$addrId=' . $addrId;
//            echo '$driver_id=' . $driver_id;
//            echo '$order_id=' . $order_id;
//
////            $newOrder=true;
//            $this->InsertOrderRows($order, $order_id, $newOrder);
        }
    }

    public function InsertOrderInfo($newOrder, $arData)
    {
        if (User::isAdmin() || User::isOperator()) {
            $operatorId = Yii::$app->user->id;
            $status = 2;
        } else {
            $status = 1;
            $operatorId = '';
        }

        $firmId = $arData['firmId'];
        $comment = $arData['comment'];
        $addrId = $arData['addrId'];
        $deliveryTime = $arData['deliveryTime'];
        $driver_id = $arData['driver_id'];
        $orderTime = $arData['orderTime'];
        $clientOrder = $arData['clientOrder'];
        $telId = $arData['telId'];
        $order = $arData['order'];

        if ($newOrder) {
            $orderInfoModel = new OrderInfo();
            $orderInfoModel->firm_id = $firmId;
            $orderInfoModel->address_id = $addrId;
            $orderInfoModel->telephone_id = $telId;

        } else {
            $orderInfoModel = OrderInfo::find()->SameOrder($addrId, $telId, $firmId, $deliveryTime);
        }

        $orderInfoModel->delivery_time = $deliveryTime;
        $orderInfoModel->comment = $comment;
        $orderInfoModel->driver_id = $driver_id;
        $orderInfoModel->order_time = $orderTime;
        $orderInfoModel->status = $status;

        if (!$clientOrder) {
            $orderInfoModel->operator_id = $operatorId;
        }

        if (!$orderInfoModel->save()) print_r($orderInfoModel->getErrors());

//        print_r('$newOrder');
//        print_r($orderInfoModel);
        if ($newOrder) {
            $order_id = Yii::$app->db->lastInsertID;
        } else {
            $order_id = $orderInfoModel->id;
        }


        $this->InsertOrderRows($order, $order_id, $newOrder);

    }

    public function InsertOrderRows($order, $order_id, $newOrder)
    {

        foreach ($order as $key => $row) {
            if ($row['complexId']) {
                $lastComplex = OrderSuborderComplex::find()->orderBy('id DESC')->one();
                $compositionId = $lastComplex['composition_id'] + 1;
                foreach ($row['dish_id'] as $dish_key => $dish_id) {
                    $complexSuborder = new OrderSuborderComplex();
                    $complexSuborder->dish_id = $dish_id;
                    $complexSuborder->composition_id = $compositionId;
                    $complexSuborder->save();
                }
                $orderSuborder = new OrderSuborder();

                $orderSuborder->complex_id = $row['complexId'];
                $orderSuborder->complex_composition = $compositionId;
                $orderSuborder->quantity = $row['count'];

                $orderSuborder->save();
                $suborder_id = Yii::$app->db->lastInsertID;

                $orderMain = new OrderMain();
                $orderMain->order_info_id = $order_id;
                $orderMain->suborder_id = $suborder_id;
                $orderMain->save();
            } else {
                if (!$newOrder) {
                    print_r('$newOrder');
                    print_r($newOrder);
                    $orderSuborder = OrderMain::find()->joinWith('suborder')->where(
                        [
                            'order_info_id'      => $order_id,
                            'dish_id'            => $row['dish_id'],
                            'additional_dish_id' => $row['additional_dish_id'],
                        ])->andWhere(['!=', 'dish_id', ''])->one();
                    if (!count($orderSuborder)) {
                        $this->InsertSuborderRows($order_id, $row);
                    } else {
                        $orderSuborder->suborder->additional_dish_id = $row['additional_dish_id'];
                        $orderSuborder->suborder->dish_id = $row['dish_id'];
                        $orderSuborder->suborder->quantity += $row['count'];

                        $orderSuborder->suborder->save();
                        print_r($orderSuborder);
                        if (!$orderSuborder->save()) print_r($orderSuborder->getErrors());
                    }


                } else {
                    $this->InsertSuborderRows($order_id, $row);
                }


            }


        }
    }

    public function InsertSuborderRows($order_id, $row)
    {
        $orderSuborder = new OrderSuborder();
        $orderSuborder->dish_id = $row['dish_id'];

        if ($row['additional_dish_id']) {
            $orderSuborder->additional_dish_id = $row['additional_dish_id'];
        }
        $orderSuborder->quantity = $row['count'];

        $orderSuborder->save();
        $suborder_id = Yii::$app->db->lastInsertID;

        $orderMain = new OrderMain();
        $orderMain->order_info_id = $order_id;
        $orderMain->suborder_id = $suborder_id;
        $orderMain->save();
    }

    public function actionAddClient()
    {
        if (Yii::$app->request->isAjax) {

            $telephones = yii::$app->request->post('telephones');
//            $client_telephone   = yii::$app->request->post('clienTelephone');
            $addresses = yii::$app->request->post('address');
//            $client_address     = yii::$app->request->post('clientAddr'        );
            $adrComment = yii::$app->request->post('addrComment');
            $client_name = yii::$app->request->post('clienName');
            $client_firm = yii::$app->request->post('clientFirm');
            $firm_sphere = yii::$app->request->post('firmSphere');

            $clientData = yii::$app->request->post('clientData');
            if (is_array($clientData)) {
//                echo 'is Array';
                foreach ($clientData as $client) {
                    $data[$client['name']] = $client['value'];
                }
                $adrComment = $data['clientAddrComm'];
                $officeRoom = $data['officeRoom'];


                switch ($officeRoom) {
                    case 'office':
                        $adrComment = 'оф. ' . $adrComment;
                        break;
                    case 'room':
                        $adrComment = 'кв. ' . $adrComment;
                        break;
                    case 'else':
                        break;
                }


                $addresses = $data['clientAddr'];
                $client_name = $data['clientName'];
                $telephones = $data['clienTelephone'];

                $client_firm = $data['clientFirm'];
            }
            $data = [];
            $data['telephones'][] = $telephones;
            $data['addresses'][] = $addresses;
            $data['adrComment'] = $adrComment;
            $data['client_name'] = $client_name;
            $data['client_firm'] = $client_firm;
            $data['firm_sphere'] = $firm_sphere;

//            print_r($data);
            $this->AddClient($data);


        }
    }

    public function AddClient($data, $clientsOrder = false)
    {
        $telephones = $data['telephones'];
        $addresses = $data['addresses'];
        $adrComment = $data['adrComment'];
        $client_name = $data['client_name'];
        $client_firm = $data['client_firm'];
        $firm_sphere = $data['firm_sphere'];

        $returnData = [];


        if ($telephones && $addresses) {

            if ($clientsOrder) {
                $client_telephone = preg_replace("/[^0-9]/", '', $telephones);
                $client_telephone = strrev($client_telephone);
                $found_tel = ClientTelephones::find()->where(['client_telephone' => $client_telephone])->one();

                if ($found_tel != null) {
                    $client_id = $found_tel->client_id;
                    $returnData['client_tel_id'] = $found_tel->id;
                } else {
                    $client = new Clients();
                    $client->date = date("Y-m-d H:i:s");
                    $client->save();
                    $client_id = Yii::$app->db->lastInsertID;

                    $clientTel = new ClientTelephones();
                    $clientTel->adding_date = date("Y-m-d");
                    $clientTel->client_telephone = $client_telephone;
                    $clientTel->client_id = $client_id;
                    $clientTel->save();

                    $clientTelId = Yii::$app->db->lastInsertID;
                    $returnData['client_tel_id'] = $clientTelId;
                }

                $returnData['client_id'] = $client_id;
                $firm_id = '';
                if (trim($client_firm)) {
                    $findFirm = Firms::find()->where(['name' => trim($client_firm)])->asArray()->one();

                    if ($findFirm) {
                        $firm_id = $findFirm['id'];
                    } else {
                        $Firm = new Firms();
                        $Firm->date = date("Y-m-d H:i:s");
                        $Firm->name = $client_firm;
                        $Firm->save();
                        $firm_id = Yii::$app->db->lastInsertID;

                    }
                    $clientFirm = new ClientFirm();
                    $clientFirm->cilent_id = $client_id;
                    $clientFirm->firm_id = $firm_id;
                    $clientFirm->save();
                }

                $returnData['firm_id'] = $firm_id;


                $findAddress = FirmAddresses::find()->where(['address' => trim($addresses)])->asArray()->one();

                if ($findAddress) {
                    $addrId = $findAddress['id'];
                } else {
                    $firmAddress = new FirmAddresses();
                    $firmAddress->firm_id = $firm_id;
                    $firmAddress->address = $addresses;
                    $firmAddress->save();
                    $addrId = Yii::$app->db->lastInsertID;
                }


                $clientAdr = new ClientAddress();

                $clientAdr->address_id = $addrId;
                $clientAdr->cilent_id = $client_id;
                $clientAdr->adding_date = date("Y-m-d");
                $clientAdr->comment = $adrComment;
                $clientAdr->save();

                $addrId = Yii::$app->db->lastInsertID;
                $returnData['addrId'] = $addrId;
                if (trim($client_name) != '') {
                    $clientName = new ClientName();
                    $clientName->client_id = $client_id;
                    $clientName->client_name = $client_name;
                    $clientName->save();
                }
//                print_r($returnData);
                return $returnData;

            } else {
                foreach ($telephones as $key => $client_telephone) {
                    $client_telephone = preg_replace("/[^0-9]/", '', $client_telephone);
                    $client_telephone = strrev($client_telephone);

                    $found_tel = ClientTelephones::find()->where(['client_telephone' => $client_telephone])->one();


                    if ($found_tel != null) {
                        $client_id = $found_tel->client_id;
                        echo $client_id . 'клиент с таким номером телефона существует';
                    } else {

                        // echo 'sdfdsf';
                        $client = new Clients();
                        $client->date = date("Y-m-d H:i:s");

                        $client->save();


                        $client_id = Yii::$app->db->lastInsertID;


                        $returnData['client_id'] = $client_id;
//                echo '$client_id='.$client_id;
//                $client_id = 2;

//
//                echo $client_id.'$client_id';
                        $clientTel = new ClientTelephones();
                        $clientTel->adding_date = date("Y-m-d");
                        $clientTel->client_telephone = $client_telephone;
                        $clientTel->client_id = $client_id;
                        $clientTel->save();

                        $clientTelId = Yii::$app->db->lastInsertID;

                        $returnData['telephone_id'] = $clientTelId;
                        $returnData['telephone'] = $client_telephone;

                        if (trim($client_firm)) {
                            if (!is_numeric($client_firm)) {
//                            echo '<br>' . $client_firm . '=$client_firm';
                                $findFirm = Firms::find()->where(['name' => trim($client_firm)])->asArray()->one();

                                if ($findFirm) {
                                    $firm_id = $findFirm['id'];
                                } else {
                                    $Firm = new Firms();
                                    $Firm->date = date("Y-m-d H:i:s");
                                    $Firm->name = $client_firm;
                                    $Firm->save();
                                    $firm_id = Yii::$app->db->lastInsertID;

                                }
                                $returnData['firm_name'] = $client_firm;
                            } else {

                                $firm_id = $client_firm;

                                $findFirm = Firms::findOne($client_firm);
                                $returnData['firm_name'] = $findFirm->name;
//
                            }
                        } else {
                            $returnData['firm_name'] = 'Без фирмы';
                            $firm_id = 1;
                        }


                        $returnData['firm_id'] = $firm_id;

//                        echo '<br> $firm_id=' . $firm_id;
//                    echo $firm_id.'$firm_id';

                        $clientFirm = new ClientFirm();
                        $clientFirm->cilent_id = $client_id;
                        $clientFirm->firm_id = $firm_id;
                        $clientFirm->save();


                        foreach ($addresses as $key => $client_address) {

                            if (is_numeric($client_address)) {
                                $clientAdr = new ClientAddress();
                                $clientAdr->address_id = $client_address;
                                $clientAdr->cilent_id = $client_id;
                                $clientAdr->adding_date = date("Y-m-d");
                                $clientAdr->comment = $adrComment;
                                $clientAdr->save();


                                $findAdr = FirmAddresses::find()->where(['address_id' => trim($client_address)])->one();
                                $returnData['address'] = $findAdr->address;
                                $returnData['address_id'] = $client_address;


                            } else {
                                $findAddress = FirmAddresses::find()->where(['address' => trim($client_address)])->one();


                                if ($findAddress) {
                                    $addrId = $findAddress->id;

                                    $returnData['address'] = $findAddress->address;
                                    $returnData['address_id'] = $findAddress->id;

                                } else {
                                    $firmAddress = new FirmAddresses();
                                    $firmAddress->firm_id = $firm_id;
                                    $firmAddress->address = $client_address;
                                    $firmAddress->save();
                                    $addrId = Yii::$app->db->lastInsertID;

                                    $returnData['address'] = $client_address;
                                    $returnData['address_id'] = $addrId;
                                }

//                                $returnData['addrId '] = $addrId;

                                $clientAdr = new ClientAddress();

                                $clientAdr->address_id = $addrId;
                                $clientAdr->cilent_id = $client_id;
                                $clientAdr->adding_date = date("Y-m-d");
                                $clientAdr->comment = $adrComment;
                                $clientAdr->save();


                            }


                        }
                        if (trim($client_name) != '') {
                            $clientName = new ClientName();
                            $clientName->client_id = $client_id;
                            $clientName->client_name = $client_name;
                            $clientName->save();
                        }

//
//
//
//                    if (trim($firm_sphere) != '') {
//                        if (!is_numeric($firm_sphere)) {
//                            $Sphere = new Spheres();
//                            $Sphere->sphere = $firm_sphere;
//                            $Sphere->save();
//                            $sphere_id = Yii::$app->db->lastInsertID;
//                        } else {
//                            $sphere_id = $firm_sphere;
//                        }
////                    echo $sphere_id.'$sphere_id';
//                    }
//                    $foundFirmsSpheres = FirmsSphere::find()->where(['firm_id' => $firm_id])->andWhere(['sphere_id' => $sphere_id])->one();
//
//                    if ($foundFirmsSpheres == null) {
//                        $firmSphere = new FirmsSphere();
//                        $firmSphere->sphere_id = $sphere_id;
//                        $firmSphere->firm_id = $firm_id;
//                        $firmSphere->save();
//                    }
//                    echo 'клиент добавлен';


                    }
                }
            }


            return json_encode($returnData);
        } else {
            return 'Нет Номера телефона или адреса';
        }
    }


    public function actionAddFirmFromGis()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            $model = new Firms;

            $regular_zero = [
                'name'   => '/->> (.*)<<-/i',
                'sphere' => '/[^-]>>.*\n(.*)/i'
            ];
            $regular = [
                'address'   => '/^\n\n(.*)/',
                'skype'     => '/skype:(.*)/',
                'icq'       => '/icq:(.*)/',
                // 'facebook'=>'/Facebook:(.*)/',
                // 'twitter'=>'/Twitter:(.*)/',
                // 'vk'=>'/ВКонтакте:(.*)/',
                // 'site'=>'/\nhttp:\/\/(.*)/',
                'site'      => '/http:\/\/(.*)/',
                'telephone' => '/тел\. (.*)/',
                // 'instagram'=>'/Instagram:(.*)/',
                'mailto'    => '/mailto:(.*)/',

            ];
            $str = $data['newText'];


            foreach ($str as $key => $firm_data) {
                if (trim($firm_data)) {
                    if ($key == 0) {
                        foreach ($regular_zero as $reg_key => $reg) {
                            // $matches=[];
                            preg_match_all($reg, $firm_data, $matches);
                            $processed_data[$reg_key] = $matches[1][0];

                        }
                    } else {
                        foreach ($regular as $reg_key => $reg) {
                            //  $matches=[];
                            preg_match_all($reg, $firm_data, $matches);

                            if ($matches[1][0]) {

                                $processed_data[$key][$reg_key] = $matches[1];
                            }
                        }

                    }
                }

            }
            //  var_dump($processed_data);


            $name = Firms::find()->where(['name' => $processed_data['name']])->one();
            $processed_data['naaaaaame'] = $name;

            if ($name == null) {
                $model->name = $processed_data['name'];
                $model->date = date("Y-m-d H:i:s");
                $model->save();

                $firm_id = Yii::$app->db->lastInsertID;
                $processed_data['firm_id'] = $firm_id;

                $sphere = $processed_data['sphere'];
                $arSphere = explode(",", $sphere);
                unset($sphere);

                foreach ($arSphere as $sphere) {
                    $sph = Spheres::find()->where(['sphere' => trim($sphere)])->one();

                    if ($sph == null) {
                        $model = new Spheres;
                        $model->sphere = trim($sphere);
                        $model->save();
                        $sphere_id = Yii::$app->db->lastInsertID;
                    } else {
                        $sphere_id = $sph['id'];
                    }
                    // $processed_data['sph'][]=$sphere_id;
                    $sphere_model = new FirmsSphere;
                    $sphere_model->firm_id = $firm_id;
                    $sphere_model->sphere_id = $sphere_id;
                    $sphere_model->save();
                }
                foreach ($processed_data as $info) {
                    if (is_array($info)) {
                        if (isset($info['address'])) {
                            foreach ($info['address'] as $address) {
                                $addressToSite[] = $address;
                                $model = new FirmAddresses;
                                $model->firm_id = $firm_id;
                                $model->address = $address;
                                $model->save();
                                $addr_id = Yii::$app->db->lastInsertID;
                                if (isset($info['telephone'])) {
                                    foreach ($info['telephone'] as $telephone) {
                                        $model_tel = new FirmsTel;
                                        $model_tel->firm_id = $firm_id;
                                        $model_tel->address_id = $addr_id;
                                        $model_tel->telephone = $telephone;
                                        $model_tel->save();
                                    }
                                }
                            }

                        } else {
                            if (isset($info['telephone'])) {

                                $model = new FirmAddresses;
                                $model->firm_id = $firm_id;
                                $model->address = 'Без адреса';
                                $model->save();
                                $addr_id = Yii::$app->db->lastInsertID;
                                foreach ($info['telephone'] as $telephone) {
                                    $model_tel = new FirmsTel;
                                    $model_tel->firm_id = $firm_id;
                                    $model_tel->address_id = $addr_id;
                                    $model_tel->telephone = $telephone;
                                    $model_tel->save();
                                }


                            }

                        }


                        if (isset($info['site'])) {
                            foreach ($info['site'] as $site) {
                                $model = new FirmSite;
                                $model->firm_id = $firm_id;
                                $model->site = $site;
                                $model->save();
                            }
                        }
                        if (isset($info['email'])) {
                            foreach ($info['email'] as $email) {
                                $model = new FirmEmails;
                                $model->firm_id = $firm_id;
                                $model->email = $email;
                                $model->save();
                            }
                        }
                    }
                }


//
//
//

//
//
//                    foreach($data['firm']['info'] as $info)
//                    {
//                         $model = new FirmAddresses;
//                        $model->firm_id=$firm_id;
//                        $model->address=$info['address'];
//                        $model->save();
//
//                        $addr_id=Yii::$app->db->lastInsertID;
//
//                        foreach($info['tel'] as $tel)
//                        {
//                            $model_tel = new FirmsTel;
//                            $model_tel->firm_id=$firm_id;
//                            $model_tel->address_id=$addr_id;
//                            $model_tel->telephone=$tel;
//                            $model_tel->save();
//                        }
//                    }
//
//
//
//                    foreach($data['firm']['sphere'] as $sphere)
//                    {
//                         $model = new FirmsSphere;
//                        $model->firm_id=$firm_id;
//                        $model->sphere=$sphere;
//                        $model->save();
//                    }
                $processed_data['duble'] = true;
                $processed_data['siteAddress'] = $addressToSite;


            } else {
                $processed_data['duble'] = false;
            }
            return json_encode($processed_data);
        }
    }

    public function actionSessionOrder()
    {
        if (Yii::$app->request->isAjax) {
            $session = Yii::$app->session;
            $session->set('order', yii::$app->request->post('order'));
        }
    }

    public function actionSessionOrderClear()
    {
        $session = Yii::$app->session;
        $session->remove('order');
        $session->remove('client_id');
    }

    public function actionSessionClient()
    {
        if (Yii::$app->request->isAjax) {

            $client_id = yii::$app->request->post('client_id');
            $telephone_id = yii::$app->request->post('telephone_id');
            $address_id = yii::$app->request->post('address_id');
            $firm_id = yii::$app->request->post('firm_id');


            ClientTelephones::updateAll(['selected_by_default' => 0], ['client_id' => $client_id]);
            $modelTelephone = ClientTelephones::findOne($telephone_id);

            if ($modelTelephone) {
                $modelTelephone->selected_by_default = 1;
                $modelTelephone->save();
            }


            ClientAddress::updateAll(['selected_by_default' => 0], ['cilent_id' => $client_id]);
            $modelAddress = ClientAddress::find()->where(
                [
                    'address_id' => $address_id,
                    'cilent_id'  => $client_id
                ])->one();
//            print_r($modelAddress);
            if (!$modelAddress) $modelAddress = ClientAddress::findOne($address_id);

            if ($modelAddress) {
                $modelAddress->selected_by_default = 1;
                $modelAddress->save();
            }


            ClientFirm::updateAll(['selected_by_default' => 0], ['cilent_id' => $client_id]);
            $modelFirm = ClientFirm::find()->where(
                [
                    'firm_id'   => $firm_id,
                    'cilent_id' => $client_id
                ])->one();
            if ($modelFirm) {
                $modelFirm->selected_by_default = 1;
                $modelFirm->save();
            }


            $adresses = ClientAddress::find()->findAddresses($client_id);
            $arTelephones = ClientTelephones::find()->where(['client_id' => $client_id])->asArray()->all();
            $arDrivers = $this->FindDrivers();
            $arFirms = ClientFirm::find()->findFirm($client_id);

//            foreach ($adresses as $key=>$address)
//            {
//                $arAddress[]=FirmAddresses::find()->byPk($address['address_id']);
//            }
            foreach ($arTelephones as $key => $arTelephone) {

                $telephone = strrev($arTelephone['client_telephone']);
                $telephone =
                    '+' . substr($telephone, 0, 1)
                    . '(' . substr($telephone, 1, 3) . ') '
                    . substr($telephone, 4, 3) . '-'
                    . substr($telephone, 7, 2) . '-'
                    . substr($telephone, 9, 2);
                $arTelephones[$key]['client_telephone'] = $telephone;
            }


//            $arAddress

            $session = Yii::$app->session;
            $session->set('drivers', $this->FindDrivers());
            $session->set('client_firm_name', yii::$app->request->post('client_firm_name'));
            $session->set('client_address', $adresses);
            $session->set('client_telephone', $arTelephones);
            $session->set('firm_id', $firm_id);
            $session->set('client_id', $client_id);
            $session->set('telephone_id', $telephone_id);
            $session->set('address_id', $address_id);

//            $session->set('client_firm_name', yii::$app->request->post('client_firm_name'));
//            $session->set('client_firm_name', $arFirms);
//            $session->set('client_address', yii::$app->request->post('client_address'));

//            $session->set('client_telephone', yii::$app->request->post('client_telephone'));


            $processed_data['addresses'] = $adresses;
            $processed_data['telephones'] = $arTelephones;
            $processed_data['drivers'] = $arDrivers;
            $processed_data['firm_id'] = $firm_id;
            $processed_data['modelAddress'] = $modelAddress;
//            $processed_data['firms']=$arFirms;
            return json_encode($processed_data);
        }

    }

    public function actionSessionDriver()
    {
        if (Yii::$app->request->isAjax) {
            $session = Yii::$app->session;
            $session->set('driver_name', yii::$app->request->post('driver_name'));
            $session->set('driver_id', yii::$app->request->post('driver_id'));
        }
    }


    public function FindDrivers()
    {

        //$text = yii::$app->request->post('searchWord');
        //$response=Spheres::find()->findByText($text);
        $drivers = Workers::find()->drivers();

        return $drivers;


    }

}
