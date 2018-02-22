<?php

use common\models\OrderStatus;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="box box-primary">


    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'enableAjaxValidation' => true,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                    ]
    );
    ?>
    <table class="table table-striped" id="table1">
        <thead>
            <tr>
                <th>Index</th>
                <th>Category</th>
                <th>SubCategory</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>PricePerUnit</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody id="mytbody" class="tableClass">
            <?php
            $i = 1;
            foreach ($model->orderItems as $info):
                ?>
                <?php echo $form->field($info, 'item_id[]')->hiddenInput(['value' => $info->item_id, 'class' => ''])->label(false); ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $info->category->category_name ?></td>
                    <td><?php echo (empty($info->subcat->subcat_name)) ? 'not set' : $info->subcat->subcat_name ?></td>
                    <td><?php echo $info->product->product_name ?></td>
                    <td id="<?php echo 'getquan' . $info->item_id ?>"><?php echo $info->quantity ?></td>
                    <td>
                        <?php
                        if ($model->order_status_id == OrderStatus::OR_NEW || $model->order_status_id == OrderStatus::OR_INPROGRESS || $model->order_status_id == OrderStatus::OR_DISPATCHED) {
                            echo $form->field($info, 'price[]')->textInput(['class' => 'amount form-control', 'data-id' => $info->item_id, 'value' => $info->price])->label(false);
                        } else {
                            echo $form->field($info, 'price[]')->textInput(['class' => 'amount form-control', 'data-id' => $info->item_id, 'readOnly' => true, 'value' => $info->price])->label(false);
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($model->order_status_id == OrderStatus::OR_NEW || $model->order_status_id == OrderStatus::OR_INPROGRESS || $model->order_status_id == OrderStatus::OR_DISPATCHED) {
                            echo $form->field($info, 'total[]')->textInput(['class' => 'getamount form-control', 'id' => 'getamountid_' . $info->item_id, 'value' => $info->total])->label(false);
                        } else {
                            echo $form->field($info, 'total[]')->textInput(['class' => 'getamount form-control', 'id' => 'getamountid_' . $info->item_id, 'readOnly' => true, 'value' => $info->total])->label(false);
                        }
                        ?>
                    </td>
                </tr>
                <?php
                $i++;
            endforeach;
            ?>
        </tbody>
    </table>
    <div class="form-group right_align">
        <label class="col-sm-4 control-label valueleft">Total Amount</label>
        <div class="col-sm-4 valueright">
            <?php
            if ($model->order_status_id == OrderStatus::OR_NEW || $model->order_status_id == OrderStatus::OR_INPROGRESS || $model->order_status_id == OrderStatus::OR_DISPATCHED) {
                echo $form->field($info, 'total_amount')->textInput(['class' => ' total_amount form-control', 'readOnly' => true, 'value' => $info->total_amount])->label(false);
            } else {
                echo $form->field($info, 'total_amount')->textInput(['class' => ' total_amount form-control', 'readOnly' => true, 'value' => $info->total_amount])->label(false);
            }
            ?>
        </div> 
    </div>

    <div class="form-group right_align">
        <label class="col-sm-4 control-label valueleft ">Current Received Amount</label>
        <div class="col-sm-4 valueright">
            <?= $form->field($orderbilling_model, 'paid_amount')->textInput(['maxlength' => true])->label(false) ?>
        </div>
    </div>

    <div class="clearfix"> </div>
    
     <div class="form-group right_align">
        <label class="col-sm-4 control-label valueleft ">Last Received Amount</label>
        <div class="col-sm-6 valueright"><?php echo $lastpaid_amount; ?></div>


    </div>

    <div class="form-group right_align">
        <label class="col-sm-4 control-label valueleft ">Total Received Amount</label>
        <div class="col-sm-6 valueright paidamount"><?php echo $paid_amount; ?></div>


    </div>
    <div class="clearfix"> </div>
    <div class="form-group right_align">
        <label class="col-sm-4 control-label valueleft ">Pending Amount</label>
        <div class="col-sm-6 valueright pendingamt">
            <?php echo $pending_amount; ?>
        </div>
    </div>





    <div id="error_receivedamount">            
        <div class="alert alert-danger alert-dismissable">                          
        </div>
    </div> 

    <div class="clearfix"> </div>  

    <div class="box-footer right_align">
        <?= Html::submitButton('Update', ['id' => 'billingmodal', 'class' => 'disable_button btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php
$script = <<< JS
    jQuery(document).ready(function () { 
        //Display error received amount is more than pending amount------
               
                 $("#error_receivedamount").hide();
                
                 $('#orderbillings-paid_amount').keyup(function(){ 
                 if(parseFloat($(this).val()) > parseFloat($(".pendingamt").html())){
                 var cart_button = $('.disable_button');
                 cart_button.prop("disabled", true);
                 var msg = "Attention! Received amount is more than pending amount. Please check it"
                
                 $("#error_receivedamount .alert.alert-danger").text(msg);
                
                 $("#error_receivedamount").show(); 
                
                 setTimeout(function() {
                        $('#error_receivedamount').fadeOut('veryslow');
                           }, 7000);
                 }else{
         var cart_button = $('.disable_button');
                 cart_button.prop("disabled", false);
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
         
         //For Filling total amount
                 
                    calculateSuminProgress();
        
              function calculateSuminProgress() {
		var sum = 0;
		$(".getamount").each(function() {
			//add only if the value is number
			if(!isNaN(this.value) && this.value.length!=0) {
				sum += parseFloat(this.value);
			}
		});
		$(".total_amount").val(sum);
	           }
        
              $(".amount").each(function() {
            //For empty field
               $(".amount").on("click", function() {
                if ($(this).val() == 0.00)
                        $(this).val("")
                });
             $(this).keyup(function(){
             var price = $(this).val();
             var orderid= $(this).data("id");
             var quan =  $("#getquan"+orderid).html();
             var amount= quan * price;
             $("#getamountid_"+orderid).val(amount);
	     calculateSum();
			});
		});
        
          function calculateSum() {
          var sum = 0;
           $(".getamount").each(function(){
          if(!isNaN(this.value) && this.value.length!=0) {
           sum += parseFloat($(this).val());
           }
          });
        
           $(".total_amount").val(sum);
        var paid =  $(".paidamount").html();
         var total= $(".total_amount").val();
             var remaining= total - paid;
           $(".pendingamt").html(remaining);
	   }
         
         
        
    });
JS;
$this->registerJs($script, View::POS_END);
?>