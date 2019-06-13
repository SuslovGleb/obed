<?php

namespace backend\modules\dishes\controllers;

use backend\modules\dishes\models\UploadImage;
use backend\models\DishCost;
use backend\models\DishType;
use backend\models\DishDay;
use backend\models\Products;
use backend\models\DishProducts;
use yii\web\Controller;
use backend\models\DishesSearch;
use backend\models\Dishes;
use Yii;
use backend\modules\dishes\models\FormAddNewDish;
use yii\web\UploadedFile;
/**
 * Default controller for the `dishes` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $newDishModel=new FormAddNewDish();
        $newDishModel->type=DishType::dropdown();
        $searchModel = new DishesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination=false;
        return $this->render('index', [
            'newDishModel' => $newDishModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Dishes();
        $modelCost= new DishCost();
        $dishTypes=DishType::dropdownNum();



        if($model->load(Yii::$app->request->post()) && $modelCost->load(Yii::$app->request->post()))
        {
            $products=yii::$app->request->post('products');
            $weights=yii::$app->request->post('product_weight');
            if($model->replacement==0) $model->replacement=NULL;
            if ($model->save(false)) {
                $dishId=Yii::$app->db->lastInsertID;
                $modelCost->date= date("Y-m-d");
                $modelCost->dish_id=$dishId;
                $modelCost->save();
                $modelDay=new DishDay();
                $modelDay->dish_id=$dishId;

                foreach($products as $prodId=>$productName)
                {

                    $product=Products::find()
                        ->where(['like', 'product_name', trim($productName)])
                        ->andWhere(['like', 'id', $prodId])
                        ->one();


                    if(!$product)
                    {
                        $productModel=new Products();
                        $productModel->product_name=$productName;
                        $productModel->save();
                        $prodId=Yii::$app->db->lastInsertID;
                    }
                    $weight=$weights[$prodId];
                    $prodModel= new DishProducts();
                    $prodModel->dish_id=$dishId;
                    $prodModel->product_id=$prodId;
                    $prodModel->weight=$weight;
                    $prodModel->save();

//                    $prodModel->save();
                }
                return $this->redirect(['create',
//                    'id' => $model->id,
                    'dishTypes' => $dishTypes,
                    'model' => $model,
                    'modelCost' => $modelCost,
                ]);
            }



        }

//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['/default/view',
//                'id' => $model->id
//            ]);
//        }
        else {
            return $this->render('create', [
                'dishTypes' => $dishTypes,
                'model' => $model,
                'modelCost' => $modelCost,
            ]);
        }
    }

    /**
     * Updates an existing Dishes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findDishes($id);
        $modelCost= DishCost::findOne(['dish_id'=>$id]);
//        $newDishModel=new FormAddNewDish();
        $dishTypes=DishType::dropdownNum();
        $modelProducts=DishProducts::find()->joinWith('product')->where(['dish_id'=>$id])->asArray()->all();
//print_r($modelProducts);die;

        if($model->load(Yii::$app->request->post()) && $modelCost->load(Yii::$app->request->post()))
        {
            $image = UploadedFile::getInstance($model,'image');


            if($image)
            {

                $path=Yii::getAlias('@frontend').'/web/images/uploads/dishes/';

                $ext = explode(".", $image->name);
                $image_name='image_dish_'.$id.'.'.strtolower($ext['1']);

                $path=$path.$image_name;
                if (!$image->saveAs($path))
                {
                    print_r($image->getErrors());

                }
                else
                {
                    $model->image=$image_name;
                    $model->save(false);
//                    print_r($model);
                }

//                $image->saveAs($path);

            }
            else
            {
                $dish=Dishes::findOne($id);
                $model->image=$dish->image;
            }


            if($model->replacement==0) $model->replacement=NULL;
            if ($model->save(false)) {
                $dishId=$id;

                $modelCost->date= date("Y-m-d");
                $modelCost->dish_id=$dishId;
                $modelCost->save();

                DishProducts::deleteAll('dish_id = :dish_id', [':dish_id' => $id]);
                $products=yii::$app->request->post('products');
                $weights=yii::$app->request->post('product_weight');


                foreach($products as $prodId=>$productName)
                {


                    $product=Products::find()
                        ->where(['like', 'product_name', trim($productName)])
                        ->andWhere(['like', 'id', $prodId])
                        ->one();
                    
                        if(!$product)
                        {
                            $productModel=new Products();
                            $productModel->product_name=$productName;
                            $productModel->save();
                            $prodId=Yii::$app->db->lastInsertID;

                        }



                    $weight=$weights[$prodId];

                    $prodModel= new DishProducts();
                    $prodModel->dish_id=$dishId;
                    $prodModel->product_id=$prodId;
                    $prodModel->weight=$weight;
                    $prodModel->save();

//                    $prodModel->save();
                }
                return $this->redirect(['update',
                    'id' => $model->id,
                    'dishTypes' => $dishTypes,
                    'model' => $model,
                    'modelCost' => $modelCost,
                    'modelProducts' => $modelProducts,
                ]);
            }





//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'id' => $model->id,
                'dishTypes' => $dishTypes,
                'model' => $model,
                'modelCost' => $modelCost,
                'modelProducts' => $modelProducts,
            ]);
        }
    }

    /**
     * Displays a single Dishes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        return $this->render('view', [
            'model' => $this->findDishes($id),
        ]);
    }

    /**
     * Deletes an existing Dishes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findDishes($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Dishes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Dishes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDishes($id)
    {
        if (($model = Dishes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
