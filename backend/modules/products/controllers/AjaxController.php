<?php

namespace backend\modules\products\controllers;

use backend\models\ProductEnergy;
use backend\models\Products;
use backend\models\ProductSynonym;
use yii\web\Controller;
use Yii;

/**
 * Ajax controller for the `products` module
 */
class AjaxController extends Controller
{

    public function actionUpdateProduct()
    {
        if (Yii::$app->request->isAjax) {
            $changeattr = yii::$app->request->post('changeattr');
            $dVal = yii::$app->request->post('dVal');
            $product_id = yii::$app->request->post('product_id');


            
             if($changeattr=='product_name')
             {
                 $modelProducts=Products::findOne($product_id);
                 $modelProducts->product_name=$dVal;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             }
               if($changeattr=='client_synonym')
             {
                 $modelProducts=ProductSynonym::find()->where(['product_id'=>$product_id])->one();
                 if(!$modelProducts) $modelProducts=new ProductSynonym;
                 $modelProducts->client_synonym=$dVal;
                 $modelProducts->product_id=$product_id;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             } if($changeattr=='buy_synonym')
             {
                 $modelProducts=ProductSynonym::find()->where(['product_id'=>$product_id])->one();
                 if(!$modelProducts) $modelProducts=new ProductSynonym;
                 $modelProducts->product_id=$product_id;
                 $modelProducts->buy_synonym=$dVal;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             }
             if($changeattr=='fats')
             {
                 $modelProducts=ProductEnergy::find()->where(['product_id'=>$product_id])->one();
                 if(!$modelProducts) $modelProducts=new ProductEnergy;
                 $modelProducts->fats=$dVal;
                 $modelProducts->product_id=$product_id;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             }if($changeattr=='proteins')
             {
                 $modelProducts=ProductEnergy::find()->where(['product_id'=>$product_id])->one();
                 if(!$modelProducts) $modelProducts=new ProductEnergy;
                 $modelProducts->proteins=$dVal;
                 $modelProducts->product_id=$product_id;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             }if($changeattr=='carbohydrates')
             {
                 $modelProducts=ProductEnergy::find()->where(['product_id'=>$product_id])->one();
                 if(!$modelProducts) $modelProducts=new ProductEnergy;
                 $modelProducts->carbohydrates=$dVal;
                 $modelProducts->product_id=$product_id;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             }if($changeattr=='calories')
             {
                 $modelProducts=ProductEnergy::find()->where(['product_id'=>$product_id])->one();
                 if(!$modelProducts) $modelProducts=new ProductEnergy;
                 $modelProducts->calories=$dVal;
                 $modelProducts->product_id=$product_id;
                 if (!$modelProducts->save())
                     print_r($modelProducts->getErrors());
             }



        }
    }

    
}
