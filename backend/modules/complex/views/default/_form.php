<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\complex\assets\ComplexAsset;
use common\widgets\DishInputNum;
use yii\bootstrap\Tabs;

$bundle = ComplexAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\Complexes */
/* @var $form yii\widgets\ActiveForm */
$complexId = $_GET['id'];
//var_dump($complexWeights->complexMenus);
?>


    <style>
        .input-check label:before {
            background: rgba(0, 0, 0, 0) url(<?=$bundle->baseUrl;?>/images/checkbox_RedGreen.png) no-repeat;
        }
    </style>
    <div class="complexes-form ComplexBox">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <!--    --><?//= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'price')->textInput()->textInput(['type' => 'number', 'min' => 0]) ?>

        <!--    --><?//= $form->field($model, 'active')->textInput() ?>


        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
if (!$model->isNewRecord) {

    ?>
    <div class="DishesByDay DishesBox" style="">
        <?php
        $i = 0;

        foreach ($allDishesbyDay as $day => $Dishes) {

            $tabItems[$i]['label'] = $day;
            $tabItems[$i]['content'] = '';

//        $tabItems[]['label']=
//        'label' => 'Заголовок вкладки 1',
//            'content' => 'Вкладка 1',
//            'active' => true // указывает на активность вкладки
            foreach ($Dishes as $key => $dish) {
                $types[$i][$dish['dishType']['type']]['type_id'] = $dish['dishType']['id'];
                $types[$i][$dish['dishType']['type']][$dish['id']] = $dish['name'];

            }

            $i++;
        }
//        print_r($types);
        foreach ($types as $dayNum => $type) {
            $typeN = 0;
            foreach ($type as $typeName => $dishes) {
                $weight=1;
                $type_id=$type[$typeName]['type_id'];
                $typeFlag = false;
                foreach ($model->complexTypes as $complexMenu) {
                    if ($complexMenu->type == $typeName) {
                        foreach ($complexWeights->complexMenus as $menuData) {

                            if ($menuData->type_id == $complexMenu->id) {

                                $weight = $menuData->weight;

                            }
                        }
//
                        $typeFlag = true;
                    }
                }

                if ($typeFlag) {
                    $checkboxChecked='checked';
                    $disabled='';
                } else {
                   $checkboxChecked='';
                    $disabled='disabled';
                }
                $tabItems[$dayNum]['content'] .= "<div class='complexType $disabled' complexId='$complexId' dayNum='$dayNum' type_id='$type_id'>";
                $checkbox = '<div class="material-switch pull-right">
                                <input id="typeActive' .$dayNum. $type_id . '" name="typeActive' .$dayNum. $type_id . '" type="checkbox"'. $checkboxChecked.'/>
                                <label for="typeActive' .$dayNum. $type_id . '" class="label-success"></label>
                            </div>';

                $tabItems[$dayNum]['content'] .= '<div class="input-check" style="float: left;margin-top: 14px;margin-left:  20px;">
                                        <input type="checkbox" id="' . $dayNum . '_type_' . $type_id . '" checked/>
                                        <label for="' . $dayNum . '_type_' . $dish_id . '"></label>
                                    </div>';

                $tabItems[$dayNum]['content'] .= "<div class='type_name'>$typeName $checkbox <div class='type_weight'>Вес:" . DishInputNum::widget(
                        [
                            'inputOptions' => 'style="margin-top: 11px;"',
                            'value'        => $weight,
                            'quantId'      => $type_id
                        ]) . " </div></div>";
                foreach ($dishes as $dish_id => $dish_name) {
                    if ($dish_id != 'type_id') {
                        $tabItems[$dayNum]['content'] .= "<div class='addDishToComplexFromTabs'  dish_id='$dish_id' complex_id='$complexId'>";

                        foreach ($modelDishes as $dish) {
                            if ($dish["id"] == $dish_id) {
                                $tabItems[$dayNum][$dish_id]['check'] = true;
                                $tabItems[$dayNum]['content'] .= '<div class="input-check" style="float: left;">
                                        <input type="checkbox" id="' . $dayNum . '_dish_' . $dish_id . '" checked/>
                                        <label for="' . $dayNum . '_dish_' . $dish_id . '"></label>
                                    </div>';
                            }

                        }

                        if (!$tabItems[$dayNum][$dish_id]['check']) {
                            $tabItems[$dayNum][$dish_id]['check'] = false;
                            $tabItems[$dayNum]['content'] .= '<div class="input-check" style="float: left;">
                                        <input type="checkbox" id="' . $dayNum . '_dish_' . $dish_id . '"/>
                                        <label for="' . $dayNum . '_dish_' . $dish_id . '"></label>
                                    </div>';
                        }
                        $tabItems[$dayNum]['content'] .= " <div class='complexDishName'>$dish_name</div></div>";
                    }
                }
                $tabItems[$dayNum]['content'] .= "</div>";

            }
        }
        echo Tabs::widget(
            [
                'items' => $tabItems,
                'options' => [
                    'class' => 'nav-tab',
                    'style' => [
//                    'width'=>count($dishTypes)*130 . 'px',
//'margin'=>'0 auto',
//'background-color'=>'#1b3e00b5',
//
//'text-shadow'=> '2px 2px 2px black',

                    ],

                ], // set this to nav-tab to get tab-styled navigation
            ]);
        ?>
    </div>
    <?php
}
