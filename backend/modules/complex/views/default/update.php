<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Complexes */

$this->title = 'Изменить комплекс: ' .$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Комплексы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="complexes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'complexWeights' => $complexWeights,
        'menuModel' => $menuModel,
        'model' => $model,
        'modelDishes' => $modelDishes,
        'allDishesbyDay' => $allDishesbyDay,
    ]) ?>

</div>
