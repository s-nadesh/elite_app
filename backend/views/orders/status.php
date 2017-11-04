
<?php

use common\models\OrderStatus;
use common\models\Products;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Products */
/* @var $form ActiveForm */

$new_order = ArrayHelper::map(OrderStatus::find()->where('status=:id and status_position_id!=:id1', ['id' => 1, 'id1' => 3])->all(), 'status_position_id', 'status_name');

$inprogress = ArrayHelper::map(OrderStatus::find()->where('status=:id and status_position_id!=:id1 and status_position_id!=:id2', ['id' => 1, 'id1' => 3, 'id2' => 1])->all(), 'status_position_id', 'status_name');


$dispatch = ArrayHelper::map(OrderStatus::find()->where('status=:id and status_position_id=:id1 or status_position_id=:id2', ['id' => 1, 'id1' => 4, 'id2' => 5])->all(), 'status_position_id', 'status_name');
?>
<?php
$form = ActiveForm::begin([
            'id' => 'active-form',
            'options' => [
                'class' => 'form-horizontal',
            ],
            'fieldConfig' => [
                'template' => "{label}<div class=\"col-sm-12\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
                ]
);
?>
<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-4 control-label valueleft">Order Status</label>
        <div class="col-sm-6 valueright">
            <?php if ((!$model->isNewRecord) && ($model->order_status_id == "1")) { ?>
                <?= $form->field($model, 'order_status_id')->dropDownList($new_order, ['prompt' => '--Select Option--'])->label(false); ?>
            <?php } elseif ((!$model->isNewRecord) && ($model->order_status_id == "2")) { ?>
                <?= $form->field($model, 'order_status_id')->dropDownList($inprogress, ['prompt' => '--Select Option--'])->label(false); ?>
            <?php } elseif ((!$model->isNewRecord) && ($model->order_status_id == "4")) { ?>    
                <?= $form->field($model, 'order_status_id')->dropDownList($dispatch, ['prompt' => '--Select Option--'])->label(false); ?>
            <?php } elseif ((!$model->isNewRecord) && ($model->order_status_id == "5")) { ?>    
                <div class="col-sm-5 valueright"><?php echo 'Delivered'; ?></div>
            <?php } ?>
        </div>
    </div>
    <div class="clearfix"> </div>  

    <div class="status6" id="delivered_box">

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Reason for Cancel order</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'cancel_comment')->textarea()->label(false) ?>
            </div>
        </div>
    </div>


    <div class="status4" id="delivered_box">

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Tracking Id</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'dispatch_track_id')->textInput()->label(false) ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Courier Company Name</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'dispatch_courier_comapny')->textInput()->label(false) ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Comments</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'dispatch_comment')->textarea()->label(false) ?>
            </div>
        </div>
    </div>

    <div class="status5" id="delivered_box">

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Delivered To</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'deliver_to')->textInput()->label(false) ?>
            </div>
        </div> 

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Mobile Number</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'deliver_phone')->textInput()->label(false); ?>
            </div> 
        </div>

        <div class="form-group">
            <label class="col-sm-4 control-label valueleft">Address</label>
            <div class="col-sm-6 valueright">
                <?= $form->field($tmodel, 'deliver_address')->textarea()->label(false) ?>
            </div> 
        </div>
    </div>

</div>

<div class="modal-footer">
    <div class="col-lg-12">
        <?php echo Html::submitButton($model->isNewRecord ? 'Change Status' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div> </div>

<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
                   jQuery(document).ready(function () { 


          $(".status6").hide();
          $(".status4").hide();
          $(".status5").hide();
                
     $(document.body).on('change', '#orders-order_status_id', function() {
                
          $(".status6").hide();
          $(".status4").hide();
          $(".status5").hide();
                
        var statid = $(this).val(); 
        
         if(statid == 6){ 
            $(".status6").show();
           } 
        
         if(statid == 4){  
            $(".status4").show();
           } 
        
         if(statid == 5){  
            $(".status5").show();
           } 
        
       });
               });
        
JS;
$this->registerJs($script, View::POS_END);
?>