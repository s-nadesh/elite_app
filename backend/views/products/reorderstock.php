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
            ]
        ]);
?>
<div class="box-body">
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Current Stock</label>
        <div class="col-sm-6 valueright"><?php echo $model->stock; ?></div>
    </div>
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
<?php ActiveForm::end(); ?>