<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserTypes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box-body">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-horizontal'
                ],
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>

    <?= $form->field($model, 'type_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'visible_site')->checkbox(['maxlength' => true]) ?>

    <?= $form->field($model, 'reorder_notify')->checkbox(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->checkbox(['maxlength' => true]) ?>

    <div class="form-group">
        <div class="col-sm-0 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
