<?php

use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\CheckboxColumn;
/* @var $this yii\web\View */

use backend\models\Dishes;
use backend\models\DishType;
use backend\models\Complexes;

$this->title = 'My Yii Application';
?>
<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],


        [
            'attribute' => 'name',
            'format' => 'html',
            'value' => function ($data) {
                if ($data->image) {
                    return Html::img('/images/complexes/' . $data->image,
                            ['width' => '120px']) . $data->name;
                } else {
                    return '';
                }
            },
        ],
        [
            'attribute' => 'price',

        ],
        [
            'attribute' => 'active',
            'value' => function ($data) {
                if ($data->active) {
                    return 'Да';
                } else {
                    return 'Нет';
                }
            },
            'filter' => ['1' => 'Да', '0' => 'Нет'],
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {update} {delete}{link}',
        ],

    ],


]);
?>
<pre>
<?php
//print_r($dishes);
?>
</pre>