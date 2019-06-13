<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\modules\products\assets\ProductsAsset;
use yii\helpers\Url;
$bundle=ProductsAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\models\productsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
//print_r($dataProvider->getPagination()->getPageSize());
//echo '<pre>';
//print_r($dataProvider->query->asArray()->all());
//echo '</pre>';
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Products', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([ 
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'product_name',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'product_name'],
                'value' => 'product_name'
            ],

            'productCategory.category',
            [
                'attribute' => 'client_synonym',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'client_synonym'],
                'value' => 'productSynonyms.client_synonym',
            ],
            [
                'attribute' => 'buy_synonym',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'buy_synonym'],
                'value' => 'productSynonyms.buy_synonym',
            ],

            [
                'attribute' => 'fats',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'fats'],
                'value' => 'productEnergies.fats',
            ],[
                'attribute' => 'proteins',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'proteins'],
                'value' => 'productEnergies.proteins',
            ],[
                'attribute' => 'carbohydrates',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'carbohydrates'],
                'value' => 'productEnergies.carbohydrates',
            ],
            [
                'attribute' => 'calories',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'calories'],
//                'value' =>$data->productEnergies->calories,
                'value' => 'productEnergies.calories',
            ],

            'product_category',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
