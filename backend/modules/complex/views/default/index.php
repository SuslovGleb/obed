<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\helpers\Url;
use backend\modules\complex\assets\ComplexAsset;
$bundle=ComplexAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ComplexesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комплексы';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .input-check label:before {
        background: rgba(0, 0, 0, 0) url(<?=$bundle->baseUrl;?>/images/checkbox_RedGreen.png) no-repeat;
    }
</style>
<div class="complexes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Complexes', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'name',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'name'],
                'value' => 'name'
            ],[
                'attribute' => 'price',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'price'],
                'value' => 'price'
            ],
//            'image',
//            'price',
            [
                'format' => 'raw',
                'attribute' => 'active',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'active'],
                'value' => function ($data) {
                    if ($data->active) {
                        return '<div class="input-check">
                                        <input type="checkbox" id="actual'.$data->id.'" default-check="1" checked/>
                                        <label for="actual'.$data->id.'"></label>
                                    </div>';
                    } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="actual'.$data->id.'" default-check="0"/>
                                        <label for="actual'.$data->id.'"></label>
                                    </div>';
                    }
                },
                'filter' => ['1' => 'Да', '0' => 'Нет'],
            ],


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
