<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Products */
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
                    'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>

    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true])->label('Product Name<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => '--Select Type--'])->label('Category Type<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'subcat_id')->dropDownList($sub_categories, ['prompt' => '--Select Type--'])->label('Sub Category Type<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'min_reorder')->textInput() ?>

    <?= $form->field($model, 'stock')->textInput(['maxlength' => true])->label('Stock<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'price_per_unit')->textInput(['maxlength' => true])->label('Price-per-unit<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'status')->checkbox(['label' => ('Active ')])->label('Status') ?>

    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
