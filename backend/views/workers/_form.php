<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Workers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="workers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'worker_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'worker_speciality_id')->textInput() ?>

    <?= $form->field($model, 'worker_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'worker_bithday')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
