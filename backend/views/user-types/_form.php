<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserTypes */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box-body">
    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>
    <?= $form->field($model, 'type_name')->textInput(['maxlength' => true])->label('Type Name<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'type_code')->textInput(['maxlength' => true])->label('Type Code<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'visible_site')->checkbox(['label' => ('Active ')])->label('Visible Site') ?>

    <?= $form->field($model, 'reorder_notify')->checkbox(['label' => ('Active ')])->label('Reorder Notification') ?>
    <?php
    if ($model->isNewRecord) {
        $model->status = true;
    }
    ?>
    <?= $form->field($model, 'status')->checkbox(['label' => ('Active ')])->label('Status') ?>
    <div class="box-footer">
        <div class="col-sm-0 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
