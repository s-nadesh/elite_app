<?php

use common\models\Products;
use common\models\StockLog;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model Products */
/* @var $stock StockLog */
?>
<?php
$form = ActiveForm::begin([
            'id' => 'active-form',
            'options' => [
                'class' => 'form-horizontal'
            ],
            'fieldConfig' => [
                'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
        ]);
?>
<form role="form">
    <div class="box-body">
        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Current Stock</label>
            <div class="col-sm-6 valueright"><?php echo $model->stock; ?></div>
            <?php // $form->field($model, 'quantity')->textInput(['maxlength' => true])->label('Current Stock') ?>
        </div>
        <div class="clearfix"> </div>
        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Reorder Quantity</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($stock, 'adjust_quantity')->textInput(['maxlength' => true])->label(false) ?>
            </div>
        </div>
        <div class="clearfix"> </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <?= Html::submitButton('Add Stock', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
    <!-- /.box-footer -->
</form>
<?php ActiveForm::end(); ?>

