<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//print_r($orderbilling_model->paid_amount);exit;
?>
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Change Order Status</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'class' => 'form-horizontal',
                ],
                    ]
    );
    ?>

    <form role="form">
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label valueleft">Total Amount</label>
                <div class="col-sm-6 valueright">
                    <?php echo $model->total_amount; ?>
                </div>
            </div>
            <div class="clearfix"> </div>
            <div class="form-group">
                <label class="col-sm-4 control-label valueleft">Paid Amount</label>
                <div class="col-sm-6 valueright">
                    <?php echo $paid_amount; ?>
                </div>
            </div>
            <div class="clearfix"> </div>
            <div class="form-group">
                <label class="col-sm-4 control-label valueleft">Pending Amount</label>
                <div class="col-sm-6 valueright pendingamt">
                    <?php echo $pending_amount; ?>
                </div>
            </div>

            <div class="clearfix"> </div>


            <div class="form-group">
                <label class="col-sm-4 control-label valueleft">Current Received Amount</label>
                <div class="col-sm-6 valueright">
                    <?= $form->field($orderbilling_model, 'paid_amount')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
            <div id="error_receivedamount">            
                <div class="alert alert-danger alert-dismissable">                          
                </div>
            </div> 

        </div><!-- /.box-body -->
        <div class="clearfix"> </div>  

        <div class="box-footer">
            <?= Html::submitButton($orderbilling_model->isNewRecord ? 'Update Amount' : 'Update', ['id' => 'billingmodal', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </form>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS
    jQuery(document).ready(function () { 
        //Display error received amount is more than pending amount------
              
                 $("#error_receivedamount").hide();
                
                 $('#orderbillings-paid_amount').keyup(function(){ 
                 if(parseFloat($(this).val()) > parseFloat($(".pendingamt").html())){
                
                 var msg = "Attention! Received amount is more than pending amount. Please check it"
                
                 $("#error_receivedamount .alert.alert-danger").text(msg);
                
                 $("#error_receivedamount").show(); 
                
                 setTimeout(function() {
                        $('#error_receivedamount').fadeOut('veryslow');
                           }, 7000);
                 }
                
                 });
        
         $('body').on('keyup','#billingmodal',function (e) {
         var pending = {$pending_amount};
        var paid_amount  = $('#orderbillings-paid_amount').val();   
          if(paid_amount > pending){
         $('#changePaymentContent').modal('show');
         e.preventDefault();
        }
        });
         
    });
JS;
$this->registerJs($script, View::POS_END);
?>