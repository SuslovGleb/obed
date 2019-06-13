<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\WorkersSpeciality */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="workers-speciality-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'speciality')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
