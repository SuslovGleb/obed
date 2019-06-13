<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Dishes */

$this->title = 'Создать блюдо';
$this->params['breadcrumbs'][] = ['label' => 'Блюда', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dishes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dishTypes' => $dishTypes,
        'modelCost' => $modelCost,
    ]) ?>

</div>
