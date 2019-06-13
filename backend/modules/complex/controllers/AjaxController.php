<?php

namespace backend\modules\complex\controllers;

use backend\models\Complexes;
use backend\models\ComplexMenu;
use backend\models\DishCompose;
use backend\models\DishCost;
use backend\models\DishDay;
use backend\models\DishType;
use backend\models\Products;
use yii\web\Controller;
use backend\models\DishesSearch;
use backend\models\Dishes;
use Yii;
use backend\modules\dishes\models\FormAddNewDish;
use yii\web\UploadedFile;
use yii\web\Response;

use yii\widgets\ActiveForm;
/**
 * Default controller for the `dishes` module
 */
class AjaxController extends Controller
{

    public function actionFindDishes()
    {
        if (Yii::$app->request->isAjax) {
            $text = yii::$app->request->post('searchWord');
            //$response=Spheres::find()->findByText($text);
            $response=Dishes::find()->where(['like', 'name', $text])->andWhere(['actual'=>'1'])/*->limit(20)*/->asArray()->all();
            return json_encode($response);
        }
    }
//
//        public function actionValidateNewDish()
//    {
//        $model = new FormAddNewDish();
//        $request = \Yii::$app->getRequest();
//        if ($request->isPost && $model->load($request->post())) {
//            \Yii::$app->response->format = Response::FORMAT_JSON;
//            if(!count(ActiveForm::validate($model)))
//            {
//                var_dump(Yii::$app->request->post());
//                var_dump($_FILES);
//            }
//            else {
//                return ActiveForm::validate($model);
//            }
//        }
//    }
//
//    public function actionAddNewDish()
//    {
//
//        if (Yii::$app->request->isAjax) {
//            var_dump(Yii::$app->request->post());
//            var_dump($_FILES);
////
////            $model = new UploadImage();
////            $model->load(Yii::$app->request->post(), '');
////
////            $path = Yii::getAlias('@frontend') .'/web/upload/dishes/images/';
////            //return $path;
////
////            $model->imageFile = UploadedFile::getInstanceByName('imageFile');
////            return json_encode($model->imageFile); # <<--- shoes nothing
//////            $model->path = $path;
//////            if($model->upload()){
//////                return true;
//////            }else{
//////                return $model->getErrors();
//////            }
//
//        }
//    }
    public function actionUpdateComplex()
    {
        if (Yii::$app->request->isAjax) {
            $changeattr = yii::$app->request->post('changeattr');
            $dVal = yii::$app->request->post('dVal');
            $compl_id = yii::$app->request->post('compl_id');

            $complModel=Complexes::findOne($compl_id);

            if($changeattr=='name')
            {
                $complModel->name=$dVal;
                print_r($complModel);
                if (!$complModel->save())
                    print_r($complModel->getErrors());
            }
            if($changeattr=='price')
            {
                $complModel->price=$dVal;
                print_r($complModel);
                if (!$complModel->save())
                    print_r($complModel->getErrors());

            }if($changeattr=='weight')
            {
                $type_id=yii::$app->request->post('type_id');
                $weight=yii::$app->request->post('weight');
                $weightModel=ComplexMenu::find()->where(['type_id'=>$type_id,'complex_id'=>$compl_id])->one();
                if(!$weightModel)
                {
                    $weightModel=new ComplexMenu();
                    $weightModel->type_id=$type_id;
                    $weightModel->complex_id=$compl_id;
                    $weightModel->weight=$weight;
                }
                else
                {
                    $weightModel->weight=$weight;
                }
                if (!$weightModel->save())
                    print_r($weightModel->getErrors());
            }
            if($changeattr=='type')
            {
                $type_id=yii::$app->request->post('type_id');
                $type_flag=yii::$app->request->post('type_flag');

                $menuModel=ComplexMenu::find()->where(['type_id'=>$type_id,'complex_id'=>$compl_id])->one();
                if($menuModel)$menuModel->delete();
                if($type_flag=='true')
                {
                    $menuModel=new ComplexMenu();
                    $menuModel->type_id=$type_id;
                    $menuModel->complex_id=$compl_id;
                    $menuModel->weight=1;

                    if (!$menuModel->save())
                        print_r($menuModel->getErrors());
                }

            }
            if($changeattr=='dish')
            {
                $dish_id=yii::$app->request->post('dish_id');
                $dish_flag=yii::$app->request->post('dish_flag');

                $composeModel=DishCompose::find()->where(['dish_id'=>$dish_id,'complex_id'=>$compl_id])->one();

                if($composeModel)$composeModel->delete();

                if($dish_flag=='true')
                {
                    $composeModel=new DishCompose();
                    $composeModel->dish_id=$dish_id;
                    $composeModel->complex_id=$compl_id;

                    if (!$composeModel->save())
                        print_r($complModel->getErrors());
                }

            }
        }
    }

    public function actionUpdateActive()
    {
        if (Yii::$app->request->isAjax) {


            $flag = yii::$app->request->post('flag');
            $flag= $flag === 'true'? 1: 0;
            $compl_id = yii::$app->request->post('compl_id');
var_dump($flag);
            $complModel=Complexes::findOne($compl_id);
            $complModel->active=$flag;
            if (!$complModel->save())
                print_r($complModel->getErrors());

        }
    }
}
