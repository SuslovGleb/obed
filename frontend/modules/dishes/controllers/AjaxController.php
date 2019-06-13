<?php
namespace frontend\modules\dishes\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\DishesSoldOut;

/**
 * Ajax controller for the `dishes` module
 */
class AjaxController extends Controller
{

    public function actionSwitchActiveDish()
    {
        if (Yii::$app->request->isAjax)
        {
            $dish_id = yii::$app->request->post('dish_id');
            $date = date("Y-m-d");

            if(yii::$app->request->post('checked')=='true')
            {
                $activeDish=DishesSoldOut::find()->where(['date'=>$date,'dish_id'=>$dish_id])->one();
                if($activeDish)
                {
                    $activeDish->delete();
                }
            }
            elseif (yii::$app->request->post('checked')=='false')
            {
                $activeDish=new DishesSoldOut;
                $activeDish->dish_id=$dish_id;
                $activeDish->date=$date;
                $activeDish->save();
            }


        }
    }
}
