<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\dishes\assets\DishesAsset;
DishesAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\Dishes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dishes-form newDishBox">

    <?php
//    $form = ActiveForm::begin([
//        'id' => 'new-dish',
//        'action'=>'/admin/dishes/ajax/add-new-dish',
//        'enableAjaxValidation' => false,
//        'validationUrl' => '/admin/dishes/ajax/validate-new-dish',
//        'options' => [
//            ['enctype' => 'multipart/form-data']
//        ],
//    ]);
//
//    echo $form->field($newDishModel, 'name');
//    echo $form->field($newDishModel, 'weight')->textInput(['type' => 'number']);
//    echo $form->field($newDishModel, 'cost')->textInput(['type' => 'number']);
//    echo $form->field($newDishModel, 'type')->dropDownList($newDishModel->type);
//
//    echo $form->field($newDishModel, 'image', ['enableAjaxValidation' => false])->fileInput();
//    echo Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ;
//    ActiveForm::end();
    if(!$model->replacement) $model->replacement=0;
    ?>

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'type')->textInput()->dropDownList($dishTypes) ;?>
    <?= $form->field($model, 'actual')->hiddenInput(['value' => '1'])->label(false);?>
    <?= $form->field($modelCost, 'weight')->textInput(['type' => 'number','min'=>0]) ;?>
    <?= $form->field($modelCost, 'cost')->textInput(['type' => 'number','min'=>0]); ?>
    <?= $form->field($model, 'replacement')->dropDownList($dishTypes); ?>
    <?=Html::img('/images/uploads/dishes/' . $model->image,
    ['width' => '120px',
    'class'=>'blue']);?>
    <?= $form->field($model, 'image')->fileInput() ?>
    <div class="form-group productsBox weighCalculation" >
        <p style="float:  left;">Расчет на </p>
        <input type="number" value="<?=$modelCost->weight?>" style="
                            float:  left;
                            margin-left:  8px;
                            width:  58px;">
        <select style="
                float:  left;
                margin:  0;
                height: 28px;">
            <option value="1" selected>г</option>
            <option value="1000">кг</option>
        </select>
    </div>
    <div class="form-group productsBox">
        <p class="addOneInputLabel" >Добавить полуфабрикат</p>
        <div class="divBtn glyphicon addOneSemifinished glyphicon-plus"></div>
    </div>
    <div class="form-group productsBox">
        <p class="addOneInputLabel" >Добавить продукт</p>
        <div class="divBtn glyphicon addOneInput glyphicon-plus"></div>
    </div>

    <?php
//    print_r($modelProducts);
        if(!$model->isNewRecord)
        {
            foreach ($modelProducts as $product)
            {
                echo ' <div class="form-group addProduct" product_id="'.$product['product_id'].'">
                    <label>Продукт: </label><input type="text" value="'.$product['product']['product_name'].'" class="form-control dish_product" name="products['.$product['product_id'].']" maxlength="150">
                    <label>Вес: </label> <input value="'.$product['weight'].'" type="text" class="form-control dish_product_weight" name="product_weight['.$product['product_id'].']" maxlength="150">
                    <div class="divBtn glyphicon applyProduct glyphicon-ok"></div>
                </div>';
            }
        }
    ?>


<!--    --><?//= $form->field($model, 'actual')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'replacement')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <div class="form-group saveBtnGroup">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
