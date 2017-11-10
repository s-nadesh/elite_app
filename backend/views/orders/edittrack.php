<?php

use common\models\OrderStatus;
use common\models\Products;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Products */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'id' => 'active-form',
            'options' => [
                'class' => 'form-horizontal',
            ],
                ]
);
?>

<div class="form-group">
    <label class="col-sm-4">Order Track Status</label>
    <div class="col-sm-6">
        <?php echo $order_track->orderStatus->status_name; ?>
    </div>
</div>
<div class="clearfix"> </div>  

<div class="change_status_fields" id="status_<?php echo OrderStatus::OR_CANCELED ?>">
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Reason for Cancel order</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'cancel_comment')->textarea()->label(false) ?>
        </div>
    </div>
</div>

<div class="change_status_fields" id="status_<?php echo OrderStatus::OR_DISPATCHED ?>">
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Tracking Id</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'dispatch_track_id')->textInput()->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Courier Company Name</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'dispatch_courier_comapny')->textInput()->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Comments</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'dispatch_comment')->textarea()->label(false) ?>
        </div>
    </div>
</div>

<div class="change_status_fields" id="status_<?php echo OrderStatus::OR_DELEVERED ?>">
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Delivered To</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'deliver_to')->textInput()->label(false) ?>
        </div>
    </div> 
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Mobile Number</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'deliver_phone')->textInput()->label(false); ?>
        </div> 
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Address</label>
        <div class="col-sm-6 valueright">
            <?= $form->field($model, 'deliver_address')->textarea()->label(false) ?>
        </div> 
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12">
        <?= Html::submitButton('Edit', ['class' => 'btn btn-primary']); ?>
    </div>
</div>


<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
    jQuery(document).ready(function () { 
        $(".change_status_fields").hide();
        $("#status_"+{$order_track->order_status_id}).show();
    });
JS;
$this->registerJs($script, View::POS_END);
?>