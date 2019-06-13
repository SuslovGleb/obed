<?php

namespace frontend\modules\complex\controllers;
use yii\web\Controller;
use frontend\models\DishesSearch;
use frontend\models\Dishes;
use Yii;

/**
 * Default controller for the `dishes` module
 */
class AjaxController extends Controller
{
    /**
     * actionSessionComplex
     */
//    public function actionSessionComplex()
//    {
//        if (Yii::$app->request->isAjax) {
//            $session = Yii::$app->session;
//            $session->set('complexBox', yii::$app->request->post('view'));
//        }
//    }
    /**
     * actionPjaxComplexDish
     */
//    public function actionPjaxComplexDish($dishType = 1, $complexId = 0)
//    {
//        //$complexId = Yii::$app->session->get('complexId',0);
//        $dishes = Dishes::find()->Dishes($complexId, $dishType);
//        return $this->render(
//            'pjaxComplexDish', [
//            'dishes' => $dishes,
//        ]);
//    }
    /**
     * actionComplexDishes
     */
//    public function actionComplexDishes()
//    {
//        if (Yii::$app->request->isAjax) {
//
//            $complex_id = yii::$app->request->post('complex_id');
//            $dish_type_id = yii::$app->request->post('dish_type_id');
//            $dishes = Dishes::find()->Dishes($complex_id, $dish_type_id);
//
//
//            //var_dump($dishes);
//        }
//
////        $DishId=yii::$app->request->post('DishId');
////        $session = new Session;
////        $session->open();
////        $session['DishId']=$DishId;
////        $session->close();
////                return $DishId;
//    }


    public function actionOneDishType()
    {


        if (Yii::$app->request->isAjax) {

            $complex_id = yii::$app->request->post('complex_id');
            $dish_type_id = yii::$app->request->post('dish_type_id');
            $dish_name = yii::$app->request->post('dish_name');
            $additionalDishFlag = yii::$app->request->post('additionalDish');
            $Add_dish_id = yii::$app->request->post('dish_id');


        }


        $dishes = Dishes::find()->Dishes($complex_id, $dish_type_id);


        return $this->renderPartial(
            'oneDishType', [
            'dishes'             => $dishes,
            'dish_name'          => $dish_name,
            'additionalDishFlag' => $additionalDishFlag,
            'Add_dish_id'        => $Add_dish_id,
            'dish_type_id'       => $dish_type_id,
            //'dishTypes'=>$dishTypes,
            //'dishType'=>$dishType,
            'complexId'          => $complex_id,
        ]);
    }

    /**
     * actionFindDishes
     */
//    public function actionFindDishes()
//    {
//        if (Yii::$app->request->isAjax) {
//            $text = yii::$app->request->post('searchWord');
//            //$response=Spheres::find()->findByText($text);
//            $response=Dishes::find()->where(['like', 'name', $text])->andWhere(['actual'=>'1'])/*->limit(20)*/->asArray()->all();
//            return json_encode($response);
//        }
//    }

}
