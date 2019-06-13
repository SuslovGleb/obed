<?php

namespace backend\modules\complex\controllers;

use backend\models\DishCompose;
use backend\models\Dishes;
use Yii;
use backend\models\Complexes;
use backend\models\ComplexesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DefaultController implements the CRUD actions for Complexes model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Complexes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ComplexesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Complexes model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Complexes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Complexes();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            $id=Yii::$app->db->lastInsertID;
//            $Dishes=yii::$app->request->post('dishes');
//
//            foreach($Dishes as $dishId=>$DishName) {
//                $compossModel=new DishCompose();
//                $compossModel->dish_id=$dishId;
//                $compossModel->complex_id=$id;
//                $compossModel->save();
//            }

            return $this->redirect(['update', [
                'model' => $model,
            ]]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Complexes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $menuModel=Complexes::find()->joinWith('complexMenus')->where(['complexes.id'=>$id])->one();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index']);

        }
        else
        {
            $allDishesbyDay['Пн']=Dishes::find()->DishByDay('Mon');
            $allDishesbyDay['Вт']=Dishes::find()->DishByDay('Tue');
            $allDishesbyDay['Ср']=Dishes::find()->DishByDay('Wed');
            $allDishesbyDay['Чт']=Dishes::find()->DishByDay('Thu');
            $allDishesbyDay['Пт']=Dishes::find()->DishByDay('Fri');


            $complexWeights=Complexes::find()->joinWith('complexMenus')->where(['complexes.id'=>$id])->one();

            $modelDishes= Dishes::find()->joinWith('dishComposes')->where(['actual'=>'1', 'complex_id'=>$id])->asArray()->all();
            return $this->render('update', [
                'complexWeights' => $complexWeights,
                'menuModel' => $menuModel,
                'model' => $model,
                'modelDishes' => $modelDishes,
                'allDishesbyDay' => $allDishesbyDay,
            ]);
        }

    }

    /**
     * Deletes an existing Complexes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Complexes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Complexes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Complexes::find()->joinWith('complexTypes')->where(['complexes.id'=>$id])->one()) !== null) {
//        if (($model = Complexes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
