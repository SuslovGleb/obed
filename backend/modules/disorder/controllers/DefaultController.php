<?php

namespace backend\modules\disorder\controllers;

use backend\models\BuffetReturn;
use Yii;
use yii\web\Controller;
use backend\models\Buffets;
use backend\models\DishType;
use backend\models\Dishes;
use backend\models\DishCost;
use backend\models\DishDay;
use backend\models\BuffetsOrder;

/**
 * Default controller for the `disorder` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionDishorderPrint()
    {

            $date=yii::$app->request->get('date');
          if(!$date)
        {

            $date = date("Y-m-d");
//            $date = '2018-02-07';
//            $date = '2016-10-28';
        }

        $day =  strftime("%a", strtotime($date));
        $arTypes=DishType::find()->asArray()->all();
        foreach($arTypes as $key=>$type)
        {
            $arDishes[$type['type']]=Dishes::find()
                ->joinWith('dishDay')
                ->joinWith('dishCost')
                ->joinWith('dishType')
                ->where(
                    [
                        $day => '1',
                        'dish_type.type' => $type['type'],
                        'actual'=>'1',
                    ])
                ->asArray()
                ->all();
        }



        $arBuffets=Buffets::find()->where(['active'=>'1'])->asArray()->all();
        foreach($arBuffets as $key=>$Buffet)
        {
            $arBuffetOrder=BuffetsOrder::find()
                ->joinWith('dish')
//                ->joinWith('dishType')
//                ->joinWith('dishCost')
                ->where(['bufet_id'=>$Buffet['id'],'date'=>$date])
                ->asArray()->all();
            $arBuffetReturn=BuffetReturn::find()->where(['bufet_id'=>$Buffet['id'],'date'=>$date])->asArray()->all();
            $arBuffets[$key]['buffetsOrders']=$arBuffetOrder;
            $arBuffets[$key]['buffetsReturn']=$arBuffetReturn;
        }
//        $arBuffets=Buffets::find()->leftJoin('buffets_order', 'buffets_order.bufet_id = buffets.id')->where(['active'=>'1','buffets_order.date'=>$date])->asArray()->all();
//        $arBuffets=Buffets::find()->joinWith('buffetsOrders')->where(['active'=>'1','buffets_order.date'=>$date])->asArray()->all();


            return $this->render('dishorder-print', [

                'date' => $date,
                'arDishes' => $arDishes,
                'arBuffets' => $arBuffets,
            ]);

    }

    function actionIndex()
    {
        $tableType= yii::$app->request->post('flagReturn');

        if (Yii::$app->request->isAjax ) {
            $date=yii::$app->request->post('date');
            $flagReturn=yii::$app->request->post('flagReturn');
        }
        else
        {
            $flagReturn=0;
            $date = date("Y-m-d");
//            $date = '2018-02-07';
//            $date = '2016-10-28';
        }
        $returnBufet=0;
        $BufetWhere=[
            'active'=>'1'
        ];
        if(yii::$app->request->get('date'))
        {
            $returnBufet=1;
            $date = yii::$app->request->get('date');
            $BufetWhere['id']=yii::$app->request->get('bufet');
        }
        $day =  strftime("%a", strtotime($date));
        $arTypes=DishType::find()->asArray()->all();

        foreach($arTypes as $key=>$type)
        {
            $dishes=Dishes::find()
                ->joinWith('dishDay')
                ->joinWith('dishCost')
                ->joinWith('dishType')
                ->where(
                    [
                        $day => '1',
                        'dish_type.type' => $type['type'],
                        'actual'=>'1',

                    ]);
//                if(yii::$app->request->get('date'))
//                {
//                    $dishes=$dishes->andWhere(['>','cost',0]);
//                }

                $dishes=$dishes->asArray()
                ->all();

            $arDishes[$type['type']]=$dishes;
        }




        $arBuffets=Buffets::find()->where($BufetWhere)->asArray()->all();
         foreach($arBuffets as $key=>$Buffet)
         {
             $arBuffetOrder=BuffetsOrder::find()->where(['bufet_id'=>$Buffet['id'],'date'=>$date])->asArray()->all();
             $arBuffetReturn=BuffetReturn::find()->where(['bufet_id'=>$Buffet['id'],'date'=>$date])->asArray()->all();
             $arBuffets[$key]['buffetsOrders']=$arBuffetOrder;
             $arBuffets[$key]['buffetsReturn']=$arBuffetReturn;
         }
//        $arBuffets=Buffets::find()->leftJoin('buffets_order', 'buffets_order.bufet_id = buffets.id')->where(['active'=>'1','buffets_order.date'=>$date])->asArray()->all();
//        $arBuffets=Buffets::find()->joinWith('buffetsOrders')->where(['active'=>'1','buffets_order.date'=>$date])->asArray()->all();

        if (Yii::$app->request->isAjax) {
            echo  $this->renderPartial('index', [
                'flagReturn'=>$flagReturn,
                'date' => $date,
                'arDishes' => $arDishes,
                'arBuffets' => $arBuffets,
            ]);
        }
        else if(yii::$app->request->get('date'))
        {
            return $this->renderAjax('index', [
                'tableType'=>'3',
                'returnBufet'=>$returnBufet,
                'flagReturn'=>$flagReturn,
                'date' => $date,
                'arDishes' => $arDishes,
                'arBuffets' => $arBuffets,
            ]);
        }
        else {
            return $this->render('index', [
                'returnBufet'=>$returnBufet,
                'flagReturn'=>$flagReturn,
                'date' => $date,
                'arDishes' => $arDishes,
                'arBuffets' => $arBuffets,
            ]);
        }
    }
    public function actionAjaxOrder()
    {

        if (Yii::$app->request->isAjax) {
            $date = yii::$app->request->post('inpDate');
            $flagReturn = yii::$app->request->post('flagReturn');
            $count = yii::$app->request->post('count');
            $dish_id = yii::$app->request->post('dish_id');
            $bufet_id = yii::$app->request->post('bufet_id');


            if($flagReturn)
            {
                $orderN = BuffetReturn::find()
                    ->where([
                        'bufet_id' => $bufet_id,
                        'date' => $date,
                        'dish_id' => $dish_id,
                    ])
                    ->one();
                if (!$orderN) {
                    $orderN = new BuffetReturn();
                }

            }
            else
            {
                $orderN = BuffetsOrder::find()
                    ->where([
                        'bufet_id' => $bufet_id,
                        'date' => $date,
                        'dish_id' => $dish_id,

                    ])
                    ->one();
                if (!$orderN) {
                    $orderN = new BuffetsOrder();
                }
            }

            if($count<1)
            {
                $orderN->delete();
            }
            else {
                $orderN->dish_id = $dish_id;
                $orderN->bufet_id = $bufet_id;
                $orderN->date = $date;
                $orderN->count = $count;

                if (!$orderN->save())
                    print_r($orderN->getErrors());
                print_r(Yii::$app->db->lastInsertID);
            }

        }
    }
}
