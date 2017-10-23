<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-horizontal'
                ]]
    );
    ?>

    <?= $form->field($model, 'name', ['labelOptions' => ['class' => 'col-sm-2 control-label'], 'template' => "{label}\n<div class='col-sm-5'>{input}</div>\n{hint}\n<div class ='errorMessage'>{error}</div>"])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address', ['labelOptions' => ['class' => 'col-sm-2 control-label'], 'template' => "{label}\n<div class='col-sm-5'>{input}</div>\n{hint}\n<div class ='errorMessage'>{error}</div>"])->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'mobile_no', ['labelOptions' => ['class' => 'col-sm-2 control-label'], 'template' => "{label}\n<div class='col-sm-5'>{input}</div>\n{hint}\n<div class ='errorMessage'>{error}</div>"])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status', ['labelOptions' => ['class' => 'col-sm-2 control-label'], 'template' => "{label}\n<div class='col-sm-offset-2 col-sm-5'>{input}</div>\n{hint}\n<div class ='errorMessage'>{error}</div>"])->checkbox() ?>

    <div class="form-group">
        <div class="col-sm-0 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
