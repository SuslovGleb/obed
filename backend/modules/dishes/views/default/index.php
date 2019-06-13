<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

use backend\models\DishType;
use kartik\widgets\FileInput;
use backend\modules\dishes\assets\DishesAsset;
use yii\helpers\Url;
$bundle=DishesAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\models\DishesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Блюда';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .input-check label:before {
        background: rgba(0, 0, 0, 0) url(<?=$bundle->baseUrl;?>/images/checkbox_RedGreen.png) no-repeat;
    }
</style>

<div class="dishes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать блюдо', ['create'], ['class' => 'btn btn-success','target'=>'_blank']) ?>


    </p>
    <div  class="selectsBox thisNotActive">
        <div style="float: left;    margin-right: 20px;">С отмечеными:</div>
        <table class="table" style="width: 200px;float: left;">
            <tr>
                <td>Пн</td>
                <td>Вт</td>
                <td>Ср</td>
                <td>Чт</td>
                <td>Пт</td>
                <td>Активность</td>
            </tr> 
            <tr>
                <?php
                    $arrChB=[
                            'Mon',
                            'Tue',
                            'Wed',
                            'Thu',
                            'Fri',
                            'active',
                    ];
                foreach($arrChB as $val)
                {
                    echo "<td><div class=\"input-check\">
                        <input type=\"checkbox\" id=\"$val\" disabled/>
                        <label for=\"$val\"></label>
                    </div></td>";
                }
                ?>

            </tr>
        </table>
        <div class="applyDayChanges btn btn-primary" style=" margin-right:  15px;margin-left:  20px;" disabled>Применить<div style="margin-left:  20px;" class=" glyphicon glyphicon-ok"></div></div>
        <div class="undoDayChanges btn btn-warning" style=" margin-right:  15px;" disabled>Вернуть как было</div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($data) {
                    if ($data->image) {
                        return Html::img('/images/uploads/dishes/' . $data->image,
                                ['width' => '120px',
                                    'class'=>'blue']).'<div class="dishImageSaveBtn glyphicon glyphicon-save"></div>';
                    } else {
                        return '<div class="dishImageSaveBtn glyphicon glyphicon-save"></div>';
                    }
                },
            ],
            [
                'attribute' => 'name',
                'format' => 'text',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'name'],
            ],
            [
                'attribute' => 'type',
                'value' => 'dishType.type',
                'format' => 'raw',
                'filter' => DishType::dropdown(),
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'selectChange','changeAttr'=>'type'],
//            'filter' => Html::activeDropDownList(
//                    $searchRole,
//                    'dishType.type',
//                    ArrayHelper::map(DishType::findAll(), 'type'),
//                    ['class' => 'form-control', 'prompt' => 'Select role']
//                )

            ],
            [
                'attribute' => 'cost',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'cost'],
                'value' => 'dishCost.cost'
            ],
            [
                'attribute' => 'weight',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'inputChange','changeAttr'=>'weight'],
                'value' => 'dishCost.weight'
            ],
            [
                'filter' => ['1' => 'Да', '0' => 'Нет'],
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'Mon'],

                'attribute' => 'Mon',
                'format' => 'raw',
                'value' =>
                    function ($data) {
                        if ($data->dishDay->Mon) {
//                            return Html::checkbox('', true, ['class'=>'radioCheck']);
                            return '<div class="input-check">
                                        <input type="checkbox" id="Mon'.$data->dishDay->id.'" default-check="1" checked/>
                                        <label for="Mon'.$data->dishDay->id.'"></label>
                                    </div>';
                        } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                            return '<div class="input-check">
                                        <input type="checkbox" id="Mon'.$data->dishDay->id.'" default-check="0" />
                                        <label for="Mon'.$data->dishDay->id.'"></label>
                                    </div>';
                        }
                    },
//                    function () {
//                return Html::checkbox('dishDay.Mon', 'dishDay.Mon');
//            },
            ],
            [
                'filter' => ['1' => 'Да', '0' => 'Нет'],
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'Tue'],
                'attribute' => 'Tue',
                'format' => 'raw',
                'value' =>
                    function ($data) {
                        if ($data->dishDay->Tue) {
//                            return Html::checkbox('', true, ['class'=>'radioCheck']);
                            return '<div class="input-check">
                                        <input type="checkbox" id="Tue'.$data->dishDay->id.'" default-check="1" checked/>
                                        <label for="Tue'.$data->dishDay->id.'"></label>
                                    </div>';
                        } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                            return '<div class="input-check">
                                        <input type="checkbox" id="Tue'.$data->dishDay->id.'" default-check="0" />
                                        <label for="Tue'.$data->dishDay->id.'"></label>
                                    </div>';
                        }
                    },
            ],
            [
                'filter' => ['1' => 'Да', '0' => 'Нет'],
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'Wed'],
                'attribute' => 'Wed',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->dishDay->Wed) {
//                        return Html::checkbox('', true, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="Wed'.$data->dishDay->id.'" default-check="1" checked/>
                                        <label for="Wed'.$data->dishDay->id.'"></label>
                                    </div>';
                    } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="Wed'.$data->dishDay->id.'" default-check="0" />
                                        <label for="Wed'.$data->dishDay->id.'"></label>
                                    </div>';
                    }
                },
            ],
            [
                'filter' => ['1' => 'Да', '0' => 'Нет'],
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'Thu'],
                'attribute' => 'Thu',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->dishDay->Thu) {
//                        return Html::checkbox('', true, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="Thu'.$data->dishDay->id.'" default-check="1" checked/>
                                        <label for="Thu'.$data->dishDay->id.'"></label>
                                    </div>';
                    } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="Thu'.$data->dishDay->id.'" default-check="0"/>
                                        <label for="Thu'.$data->dishDay->id.'"></label>
                                    </div>';
                    }
                },
            ],
            [
                'filter' => ['1' => 'Да', '0' => 'Нет'],
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'Fri'],
                'attribute' => 'Fri',
                'format' => 'raw',
                'value' => function ($data) {
                    if ($data->dishDay->Fri) {
//                        return Html::checkbox('', true, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="Fri'.$data->dishDay->id.'" default-check="1" checked/>
                                        <label for="Fri'.$data->dishDay->id.'"></label>
                                    </div>';
                    } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="Fri'.$data->dishDay->id.'" default-check="0"/>
                                        <label for="Fri'.$data->dishDay->id.'"></label>
                                    </div>';}
                },
            ],
            [
                    'format' => 'raw',
                'attribute' => 'actual',
                'contentOptions' =>['class' => 'changeOnclick','changeType'=>'checkBoxChange','changeAttr'=>'active'],
                'value' => function ($data) {
                    if ($data->actual) {
                        return '<div class="input-check">
                                        <input type="checkbox" id="actual'.$data->dishDay->id.'" default-check="1" checked/>
                                        <label for="actual'.$data->dishDay->id.'"></label>
                                    </div>';
                    } else {
//                            return Html::checkbox('', false, ['class'=>'radioCheck']);
                        return '<div class="input-check">
                                        <input type="checkbox" id="actual'.$data->dishDay->id.'" default-check="0"/>
                                        <label for="actual'.$data->dishDay->id.'"></label>
                                    </div>';
                    }
                },
                'filter' => ['1' => 'Да', '0' => 'Нет'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                            'buttons' => [
//                                'view' => function ($url, $model) {
//                                    return Html::a('<span class="glyphicon glyphicon-eye-open" title="View Details"></span>', $url, ['data-pjax' => 0, 'target' => "_blank"]);
//                                },
                                        'update' => function ($url, $model) {

                                    return Html::a('<span class="glyphicon glyphicon-pencil" title="Update"></span>',$url, ['data-pjax' => 0, 'target' => "_blank"]);
                                },
                                        'delete' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-trash" title= "Delete"></span>', $url, ['data-pjax' => 0, 'target' => "_blank"]);
                                },
                                    ],
                'template' => ' {update} {delete}',
             ],
            ],



    ]); ?>
</div>
