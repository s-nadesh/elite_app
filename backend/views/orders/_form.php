<?php

use common\models\OrderItems;
use common\models\Orders;
use common\models\OrderStatus;
use common\models\Users;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Orders */
/* @var $form ActiveForm */
$order_status = OrderStatus::prepareOrderStatus($model->order_status_id);
?>



<div class="col-md-12">
    <div class="box box-primary">

        <div class="box-header">
            <h3 class="box-title">Order Details</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-2 control-label valueleft">Invoice Date</label>
                <div class="col-sm-5 valueright">
                    <?php echo $model->invoice_date; ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label valueleft">Customer/Dealer</label>
                <div class="col-sm-5 valueright"><?php echo $model->user->name; ?></div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label valueleft">Sales Executive</label>
                <div class="col-sm-5 valueright"><?php echo $model->orderedBy->name; ?></div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Order Details</h3>

                </div>
                <div class="box-body table-responsive no-padding">

                    <table class="table table-hover" id="table1">
                        <tr>
                            <th>Serial No</th>
                            <th>Category</th>
                            <th>Sub Category</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price Per Unit</th>
                            <th>Total Amount</th>
                        </tr>
                        <tbody id="mytbody">
                            <?php
                            $i = 1;
                            foreach ($model->orderItems as $info):
                                ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $info->category_name ?></td>
                                    <td><?php echo $info->subcat_name ?></td>
                                    <td><?php echo $info->product_name ?></td>
                                    <td><?php echo $info->quantity ?></td>
                                    <td><?php echo $info->price ?></td>
                                    <td><?php echo $info->total ?></td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'active-form',
                    'options' => [
                        'class' => 'form-horizontal',
                    ],
                        ]
        );
        ?>
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Change Order Status</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label valueleft">Invoice Number</label>
                    <div class="col-sm-6 valueright">
                        <?= $form->field($model, 'invoice_no')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label valueleft">Status</label>
                    <div class="col-sm-6 valueright">
                        <?= $form->field($model, 'order_status_id')->dropDownList($order_status, ['prompt' => '--Select--'])->label(false); ?>
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

            </div><!-- /.box-body -->

            <div class="box-footer">
                <?= Html::submitButton($model->isNewRecord ? 'Place order' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div><!-- /.box -->
        <?php ActiveForm::end(); ?>
    </div>

    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Update Payment</h3>
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

                <div id="error_receivedamount">            
                    <div class="alert alert-danger alert-dismissable">                          
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label valueleft">Current Received Amount</label>
                    <div class="col-sm-6 valueright">
                        <?= $form->field($orderbilling_model, 'paid_amount')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>

            </div><!-- /.box-body -->
            <div class="clearfix"> </div>  

            <div class="box-footer">
                <?= Html::submitButton($orderbilling_model->isNewRecord ? 'Update Amount' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div><!-- /.box -->

    </div>
</div>


<?php
/* For Subcategory dropdown list */

$subcategorycallback = Yii::$app->urlManager->createUrl(['orders/getsubcategorylist']);
$productlistcallback = Yii::$app->urlManager->createUrl(['orders/getproductlist']);

$script = <<< JS
           jQuery(document).ready(function () { 
        
        
    //Get subcategory list------
        
            var categoryid  = $('#orderitems-category_id').val();
   
            $('#orderitems-category_id').on('change', function() {
            var categoryid     = $(this).val(); 
            if(categoryid!=""){  
            subcategorylist(categoryid);  
            } 
            });
              function subcategorylist(categoryid){
               $.ajax({
                 url  : "{$subcategorycallback}",
                type : "POST",                   
                data: {
                  id: categoryid,                       
                },
                success: function(data) {
                  $("#orderitems-subcat_id").html(data);
                  if(data!=""){         
                    $('#orderitems-subcat_id').val();
                            }
                        }
                  });  
               }
            
   
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
                
     //Get Product list----
                
                var subcatid  = $('#orderitems-subcat_id').val();
              
                $('#orderitems-subcat_id').on('change', function() {
                
                         var subcatid  = $(this).val(); 
                         if(subcatid!=""){  
                              get_productlist(subcatid);  
                        } 
                        });
            function get_productlist(subcatid){
            $.ajax({
                url  : "{$productlistcallback}",
                type : "POST",                   
                data: {
                  id: subcatid,                     
                },
                success: function(data) {
                  $("#orderitems-product_id").html(data);
                  if(data != ""){         
                    $('#orderitems-product_id').val();
                  }
                }
           });  
        }
                
   //Change Status----
   
         $(".change_status_fields").hide();
        $(document.body).on('change', '#orders-order_status_id', function() {
            $(".change_status_fields").hide();
            var statid = $(this).val(); 
            $("#status_"+statid).show();
          });
       
         });
JS;
$this->registerJs($script, View::POS_END);
?>