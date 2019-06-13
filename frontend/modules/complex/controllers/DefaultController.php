<?php

namespace frontend\modules\complex\controllers;

use yii\web\Controller;
use frontend\models\Complexes;
use frontend\models\ComplexMenu;
use frontend\models\DishCompose;
use frontend\models\DishCost;
use frontend\models\DishDay;
use frontend\models\DishType;
use frontend\models\Products;

use Yii;
use frontend\models\DishesSearch;
use frontend\models\ComplexesSearch;
use frontend\models\Dishes;
/**
 * Default controller for the `complex` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        $arComplexes = Complexes::find()->allComplexes();


        return $this->render(
            'index', [
            'arComplexes' => $arComplexes,
        ]);
    }
}
