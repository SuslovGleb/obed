<?php

namespace frontend\modules\dishes\controllers;

use Yii;
use frontend\models\DishType;
use yii\web\Controller;
use frontend\models\Complexes;
use frontend\models\ComplexMenu;
use frontend\models\Dishes;
/**
 * Default controller for the `dishes` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($complexImage='')
    {

//        $complexes = Complexes::find()->allComplexes();
        $dishTypes = DishType::find()->dishTypes();
//        $dishTypes = ComplexMenu::find()->dishTypes(true);
        $dishes    = Dishes::find()->Dishes();
        //$session = Yii::$app->session;


        return $this->render('index',
            [
//                'session'=>$session,
                'dishes'=>$dishes,
//                'complexes'=>$complexes,
                'dishTypes'=>$dishTypes,
//                'complexImage'=>$complexImage,
            ]
        );
    }
}
