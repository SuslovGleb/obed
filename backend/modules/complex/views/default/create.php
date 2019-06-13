<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Complexes */

$this->title = 'Создать комплекс';
$this->params['breadcrumbs'][] = ['label' => 'Комплексы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complexes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
