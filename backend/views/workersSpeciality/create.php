<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\WorkersSpeciality */

$this->title = 'Create Workers Speciality';
$this->params['breadcrumbs'][] = ['label' => 'Workers Specialities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="workers-speciality-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
