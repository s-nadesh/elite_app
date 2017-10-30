<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>
<!--<h3 class="box-title">User Details</h3>-->
<div class="box-body">

    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Name<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => !$model->isNewRecord])->label('Email<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'user_type_id')->dropDownList($items, ['prompt' => '--Select Type--'])->label('Type<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkbox(['label' => ('Active ')])->label('Status') ?>

    <!--<h3 class="box-title">Login Details</h3>-->

    <?php // $form->field($model1, 'username')->textInput(['maxlength' => true])->label('User Name<span class="required-label"></span>'); ?>

    <?php // $form->field($model1, 'password_hash')->passwordInput(['maxlength' => true])->label('Password<span class="required-label"></span>'); ?>

    <?php // $form->field($model1, 'email')->textInput(['maxlength' => true])->label('Email<span class="required-label"></span>'); ?>

    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
