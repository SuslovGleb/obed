<?php

namespace frontend\modules\order\controllers;

use frontend\models\OrderInfo;
use yii\web\Controller;

use Yii;

use frontend\models\Workers;

/**
 * Default controller for the `order` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $session->set('drivers',$this->FindDrivers());


        return $this->render('index',
            [
                'modelOrders'=>$modelOrders,
                'session'=>$session,
            ]
        );
    }


    public function actionViewAddClientBox()
    {
        return $this->renderPartial('_addClientBox',[]);
    }
    public function FindDrivers()
    {

        //$text = yii::$app->request->post('searchWord');
        //$response=Spheres::find()->findByText($text);
        $drivers=Workers::find()->drivers();

        return $drivers;


    }
}
