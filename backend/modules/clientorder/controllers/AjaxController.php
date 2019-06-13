<?php

namespace backend\modules\clientorder\controllers;


use backend\models\Complexes;
use backend\models\ComplexMenu;
use backend\models\DishCompose;
use backend\models\OrderInfo;
use backend\models\OrderMain;
use backend\models\OrderSuborder;
use backend\models\OrderSuborderComplex;
use yii\web\Controller;
use Yii;

/**
 * Ajax controller for the `clientorder` module
 */
class AjaxController extends Controller
{

    public function actionFindComplexByDishes()
    {
        if (Yii::$app->request->isAjax) {
            $ids=[];
            $arrDishesId = yii::$app->request->post('dishesId');
            foreach ($arrDishesId as $dishId)
            $modelComplexFind=DishCompose::find()
                ->joinWith('complex')
                ->where(['dish_id'=>$dishId])
                ->andWhere(['active'=>1])
                ->groupBy('complex_id')
//                ->where(['dish_id'=>$dishesId])
                ->all();

            foreach ($modelComplexFind as $key=>$val)
            {
                $ids[$val->complex_id]=$val->complex->name;
            }
            foreach ($ids as $complexId=>$complexName)
            {
                $i=0;
                $modelComplexMenu=ComplexMenu::find()->where(['complex_id'=>$complexId])->count();
                foreach ($arrDishesId as $dishId)
                {
                    $modelComplexFind=DishCompose::find()
                        ->where(['complex_id'=>$complexId])
                        ->andWhere(['dish_id'=>$dishId])
                        ->exists();
                    if($modelComplexFind)
                    {
                        $i++;
                        $ids['count'][$complexId]=$i;
                        $ids['truecount'][$complexId]=$modelComplexMenu;
                    }


                }

            }

//            print_r($modelComplexFind);
            foreach ($modelComplexFind as $key=>$val)
            {
                $ids[$val->complex_id]=$val->complex->name;
            }

            foreach ($ids as $complexId=>$complexName)
            {
                    if(
                        $ids['count'][$complexId]!=$ids['truecount'][$complexId]
                        ||count($arrDishesId)>$ids['count'][$complexId]
                    )
                    {
                        unset($ids[$complexId]);
                    }
            }
            return json_encode($ids);


        }
    }
    public function actionMarkAsPrinted()
    {
        if (Yii::$app->request->isAjax) {
            $idS = yii::$app->request->post('idS');
            $idS=explode( ',', $idS );
            $modelOrderInfos = OrderInfo::findAll($idS);
            foreach ($modelOrderInfos as $modelOrderInfo)
            {
                $modelOrderInfo->status=3;
                if (!$modelOrderInfo->save()) print_r($modelOrderInfo->getErrors());
            }



        }
    }


    public function actionDeleteOrderByIds($arId='')
    {

        if (Yii::$app->request->isAjax) {
            $arId = yii::$app->request->post('arId');
        }


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



    }
    public function actionFindPrintOrders()
{
    if (Yii::$app->request->isAjax) {

        $modelOrderInfo=OrderInfo::find()->OrderByDatePrint(date("Y-m-d"));
        $orderTable=\backend\modules\clientorder\controllers\DefaultController::orderTableFromModel($modelOrderInfo,true,true);
        return $this->renderPartial('print', [
            'modelOrderInfo' => $modelOrderInfo,
            'orderTable' => $orderTable,

        ]);
    }

}
}
