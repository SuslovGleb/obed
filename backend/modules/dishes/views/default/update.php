<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Dishes */

$this->title = 'Изменить блюдо: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Блюда', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dishes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'dishTypes' => $dishTypes,
        'model' => $model,
        'modelCost' => $modelCost,
        'modelProducts' => $modelProducts,
    ]) ?>

</div>
