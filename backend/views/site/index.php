<?php

//use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\CheckboxColumn;
/* @var $this yii\web\View */

use kartik\grid\GridView;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use backend\models\Dishes;
use backend\models\DishType;
use kartik\builder\Form;

$this->title = 'My Yii Application';
?>
<?php
//$form = ActiveForm::begin();
//$attribs = $model->formAttribs;
//unset($attribs['attributes']['color']);
//$attribs['attributes']['status'] = [
//    'type'=>TabularForm::INPUT_WIDGET,
//    'widgetClass'=>\kartik\widgets\SwitchInput::classname()
//];
//
//echo TabularForm::widget([
//    'dataProvider'=>$dataProvider,
//    'form'=>$form,
//    'attributes'=>[
//        'id'=>['label'=>'id', 'type'=>TabularForm::INPUT_HIDDEN_STATIC],
//        'image'=>[
//                'label'=>'image',
//            'type'=>TabularForm::INPUT_RAW,
//            'format' => 'html',
//            'value' => function ($data) {
//                if ($data->image) {
//                    return Html::img('/images/uploads/dishes/' . $data->image,
//                            ['width' => '120px',
//                                'class'=>'blue']).'<div class="dishImageSaveBtn glyphicon glyphicon-save"></div>';
//                } else {
//                    return '<div class="dishImageSaveBtn glyphicon glyphicon-save"></div>';
//                }
//            },
//        ],
//        'Mon'=>[
//    'type'=>TabularForm::INPUT_WIDGET,
//    'widgetClass'=>\kartik\widgets\SwitchInput::classname()
//],
//        'name'=>['label'=>'Name'],
//        'cost'=>['label'=>'cost','value' => 'dishCost.cost'],
//        'weight'=>['label'=>'weight', 'type'=>TabularForm::INPUT_STATIC],
//        'Mon'=>['label'=>'Published On', 'type'=>TabularForm::INPUT_STATIC],
//    ],
//    'gridSettings'=>[
//        'floatHeader'=>true,
//        'panel'=>[
//            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
//            'type' => GridView::TYPE_PRIMARY,
//            'after'=> Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' .
//                Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
//                Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
//        ]
//    ]
//]);
//ActiveForm::end();
echo GridView::widget([
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
            'format' => 'text'
        ],
        [
            'attribute' => 'type',
            'value' => 'dishType.type',
            'format' => 'raw',
            'filter' => DishType::dropdown(),
//            'filter' => Html::activeDropDownList(
//                    $searchRole,
//                    'dishType.type',
//                    ArrayHelper::map(DishType::findAll(), 'type'),
//                    ['class' => 'form-control', 'prompt' => 'Select role']
//                )

        ],
        [
            'attribute' => 'cost',
            'value' => 'dishCost.cost'
        ],
        [
            'attribute' => 'weight',
            'value' => 'dishCost.weight'
        ],
        [
            'filter' => ['1' => 'Да', '0' => 'Нет'],
            'attribute' => 'Mon',
            'format' => 'raw',
            'value' =>
                function ($data) {
                    if ($data->dishDay->Mon) {
                        return Html::checkbox('', true);
                    } else {
                        return Html::checkbox('', false);
                    }
                },
//                    function () {
//                return Html::checkbox('dishDay.Mon', 'dishDay.Mon');
//            },
        ],
        [
            'filter' => ['1' => 'Да', '0' => 'Нет'],
            'attribute' => 'Tue',
            'format' => 'raw',
            'value' =>
                function ($data) {
                    if ($data->dishDay->Tue) {
                        return Html::checkbox('', true);
                    } else {
                        return Html::checkbox('', false);
                    }
                },
        ],
        [
            'filter' => ['1' => 'Да', '0' => 'Нет'],
            'attribute' => 'Wed',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->dishDay->Wed) {
                    return Html::checkbox('', true);
                } else {
                    return Html::checkbox('', false);
                }
            },
        ],
        [
            'filter' => ['1' => 'Да', '0' => 'Нет'],
            'attribute' => 'Thu',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->dishDay->Thu) {
                    return Html::checkbox('', true);
                } else {
                    return Html::checkbox('', false);
                }
            },
        ],
        [
            'filter' => ['1' => 'Да', '0' => 'Нет'],
            'attribute' => 'Fri',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->dishDay->Fri) {
                    return Html::checkbox('', true);
                } else {
                    return Html::checkbox('', false);
                }
            },
        ],
        [
            'attribute' => 'actual',
            'value' => function ($data) {
                if ($data->actual) {
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