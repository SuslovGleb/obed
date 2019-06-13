<?php

namespace backend\modules\dishes\controllers;

use backend\models\DishCost;
use backend\models\DishDay;
use backend\models\DishType;
use backend\models\Products;
use backend\models\DishProducts;
use yii\web\Controller;
use backend\models\DishesSearch;
use backend\models\Dishes;
use backend\models\DishCompose;
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

    public function actionFindProduct()
    {
        if (Yii::$app->request->isAjax) {
            $text = yii::$app->request->post('searchWord');
            //$response=Spheres::find()->findByText($text);
            $response=Products::find()->where(['like', 'product_name', $text])/*->limit(20)*/->asArray()->all();
            return json_encode($response);
        }
    }
    public function actionFindSemifinished()
    {
        if (Yii::$app->request->isAjax) {
            $response=Dishes::find()->select(['name','id'])->where(['type'=>8])->asArray()->all();
            return json_encode($response);
        }
    }
    public function actionFindSemifinishedProducts()
    {
        if (Yii::$app->request->isAjax) {
            $id = yii::$app->request->post('id');
//            if (($model = Dishes::findOne($id)) !== null) {
//                return $model;
//            } else {
//                throw new NotFoundHttpException('The requested page does not exist.');
//            }

            $response=DishProducts::find()->joinWith('product')->where(['dish_id'=>$id])->asArray()->all();
            $response['dish']=DishCost::find()->select(['weight'])->where(['dish_id'=>$id])->asArray()->all();
            return json_encode($response);
        }
    }

        public function actionValidateNewDish()
    {
        $model = new FormAddNewDish();
        $request = \Yii::$app->getRequest();
        if ($request->isPost && $model->load($request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            if(!count(ActiveForm::validate($model)))
            {
                var_dump(Yii::$app->request->post());
                var_dump($_FILES);
            }
            else {
                return ActiveForm::validate($model);
            }
        }
    }

    public function actionAddNewDish()
    {

        if (Yii::$app->request->isAjax) {
            var_dump(Yii::$app->request->post());
            var_dump($_FILES);
//
//            $model = new UploadImage();
//            $model->load(Yii::$app->request->post(), '');
//
//            $path = Yii::getAlias('@frontend') .'/web/upload/dishes/images/';
//            //return $path;
//
//            $model->imageFile = UploadedFile::getInstanceByName('imageFile');
//            return json_encode($model->imageFile); # <<--- shoes nothing
////            $model->path = $path;
////            if($model->upload()){
////                return true;
////            }else{
////                return $model->getErrors();
////            }

        }
    }
    public function actionUpdateDish()
    {
        if (Yii::$app->request->isAjax) {
            $changeattr = yii::$app->request->post('changeattr');
            $dVal = yii::$app->request->post('dVal');
            $dish_id = yii::$app->request->post('dish_id');


            if($changeattr=='name')
            {
                $modelDishes=Dishes::findOne($dish_id);
                $modelDishes->name=$dVal;
                if (!$modelDishes->save())
                    print_r($modelDishes->getErrors());
            }if($changeattr=='cost')
            {
                $modelCost=DishCost::findOne(['dish_id'=>$dish_id]);
                $modelCost->cost=$dVal;
                if (!$modelCost->save())
                    print_r($modelCost->getErrors());

            }if($changeattr=='weight')
            {
                $modelCost=DishCost::findOne(['dish_id'=>$dish_id]);
                $modelCost->weight=$dVal;
                if (!$modelCost->save())
                    print_r($modelCost->getErrors());
            }if($changeattr=='type')
            {
                $modelDishes=Dishes::findOne($dish_id);
                $modelType=DishType::findOne(['type'=>$dVal]);
                $modelDishes->type=$modelType->id;
                if (!$modelDishes->save())
                    print_r($modelDishes->getErrors());
            }


        }
    }

    public function actionUpdateDays()
    {
        if (Yii::$app->request->isAjax) {


            $arDays = yii::$app->request->post('arDays');
//            print_r($arDays);
            foreach ($arDays as $index => $value) {

                foreach ($value as $dish_id => $days) {
                    $modelDay=DishDay::find()->where(['dish_id'=>$dish_id])->one();

                    if(!$modelDay) {
                        $modelDay=new DishDay();
                        $modelDay->dish_id=$dish_id;
                    }
                    if ($days['Mon']) {
                        $flag= $days['Mon'] === 'true'? 1: 0;
                        $modelDay->Mon = $flag;
                        echo '$days[\'Mon\']=' . $flag;
                    }
                    if ($days['Tue']) {
                        $flag= $days['Tue'] === 'true'? 1: 0;
                        $modelDay->Tue = $flag;
                        echo '$days[\'Tue\']=' . $flag;
                    }
                    if ($days['Wed']) {
                        $flag= $days['Wed'] === 'true'? 1: 0;
                        $modelDay->Wed = $flag;
                        echo '$days[\'Wed\']=' . $flag;
                    }
                    if ($days['Thu']) {
                        $flag= $days['Thu'] === 'true'? 1: 0;
                        $modelDay->Thu = $flag;
                        echo '$days[\'Thu\']=' . $flag;
                    }
                    if ($days['Fri']) {
                        $flag= $days['Fri'] === 'true'? 1: 0;
                        $modelDay->Fri = $flag;
                        echo '$days[\'Fri\']=' .$flag;
                    }
                    if($days['active'])
                    {

                        $flag= $days['active'] === 'true'? 1: 0;
                        $model=Dishes::find()->joinWith('dishCost')->where(['dishes.id'=>$dish_id])->one();
//                        print_r($model);
//                        print_r($model->weight);

                        $model->actual=$flag;
//                        $model->weight=$weight;
//                        $model->cost=$cost;

                        if (!$model->save())
                        {
                            print_r($model->getErrors());
                        }
//                        else
//                        {
//                            if(!$flag)
//                            {
//                                $DishComposemodel=DishCompose::deleteAll([
//                                    'dish_id' => $dish_id,
//                                ]);
//                            }
//
//                        }

                    }
                    if (!$modelDay->save())
                        print_r($modelDay->getErrors());





//
//                    $modelDay=DishDay::find()->where(['dish_id'=>$dish_id])->one();
//                    $modelDish=Dishes::find()->where(['id'=>$dish_id])->one();
//                    $modelDay->dish_id=$dish_id;

//
//                    foreach ($days as $day => $flag) {
//                        echo '$flag='.$flag;
//                        if($modelDay)
//                        {
//                            print_r($modelDay);
//                            if($day=='Mon')
//                            {
//                                $modelDay->Mon=(int)$flag;
//                            }  if($day=='Tue')
//                            {
//                                $modelDay->Tue=(int)$flag;
//                            }  if($day=='Wed')
//                            {
//                                $modelDay->Wed=(int)$flag;
//                            }  if($day=='Thu')
//                            {
//                                $modelDay->Thu=(int)$flag;
//                            }  if($day=='Fri')
//                            {
//                                $modelDay->Fri=(int)$flag;
//                            }  if($day=='active')
//                            {
//                                $modelDish->actual=(int)$flag;
//                            }
//                        }
//
////                        echo '$day=' . $day;
////                        echo '$flag=' . $$flag;
//
//                    }
//                    if (!$modelDay->save())
//                        print_r($modelDay->getErrors());
//
//                    if (!$modelDish->save())
//                        print_r($modelDay->getErrors());



                }


            }
        }
    }
}
