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
                'template' => "{label}<div class=\"\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                'labelOptions' => ['class' => 'control-label'],
            ],
                ]
);
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
           <?php echo $model->invoice_date;?>
         </div>
        </div>
        <?php  $get_username = Users::getUsername($model->user_id);
      ?>
        <div class="form-group">
            <label class="col-sm-2 control-label valueleft">Customer/Dealer</label>
            <div class="col-sm-5 valueright"><?php echo $get_username['name']; ?></div>
        </div>
         <?php  $get_orderby = Users::getUsername($model->ordered_by); ?>

        <div class="form-group">
            <label class="col-sm-2 control-label valueleft">Sales Executive</label>
            <div class="col-sm-5 valueright"><?php echo $get_orderby['name']; ?></div>
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
                        <?php if ($model->isNewRecord) { ?>
                            <th>Actions</th>
                        <?php } ?>
                    </tr>
                    <tbody id="mytbody">
                        <?php
                        if (!$model->isNewRecord) {
                            $i = 1;
                            foreach ($model->orderItems as $info):
                                ?>
                                <tr>
                                    <td><?php echo $i ?></td>
                                    <td><?php echo $info->category->category_name ?></td>
                                    <td><?php echo $info->subcat->subcat_name ?></td>
                                    <td><?php echo $info->product->product_name ?></td>
                                    <td><?php echo $info->product->stock ?></td>
                                    <td><?php echo $info->product->price_per_unit ?></td>
                                    <td><?php echo $info->total ?></td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;
                            ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Change Order Status</h3>
        </div><!-- /.box-header -->
        <!-- form start -->
        <form role="form">
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
                        <?php if ((!$model->isNewRecord) && ($model->order_status_id == "1")) { ?>
                            <?= $form->field($model, 'order_status_id')->dropDownList($new_order, ['prompt' => '--Select Option--'])->label(false); ?>
                        <?php } elseif ((!$model->isNewRecord) && ($model->order_status_id == "2")) { ?>
                            <?= $form->field($model, 'order_status_id')->dropDownList($inprogress, ['prompt' => '--Select Option--'])->label(false); ?>
                        <?php } elseif ((!$model->isNewRecord) && ($model->order_status_id == "4")) { ?>    
                            <?= $form->field($model, 'order_status_id')->dropDownList($dispatch, ['prompt' => '--Select Option--'])->label(false); ?>
                        <?php } elseif ((!$model->isNewRecord) && ($model->order_status_id == "5")) { ?>    
                            <div class="col-sm-6 valueright"><?php echo 'Delivered'; ?></div>
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

            </div><!-- /.box-body -->

            <div class="box-footer">
                <?= Html::submitButton($model->isNewRecord ? 'Place order' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </form>
    </div><!-- /.box -->
<?php ActiveForm::end(); ?>


</div>
    
<div class="col-md-6">
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
            'fieldConfig' => [
                'template' => "{label}<div class=\"\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                'labelOptions' => ['class' => 'control-label'],
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
                
                <div id="error_receivedamount">            
                    <div class="alert alert-danger alert-dismissable">                          
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-4 control-label valueleft">Current Received Amount</label>
                    <div class="col-sm-6 valueright">
                        <?= $form->field($model, 'order_id')->hiddenInput()->label(false);?>
                        <?= $form->field($orderbilling_model, 'paid_amount')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                </div>

            </div><!-- /.box-body -->
            <div class="clearfix"> </div>  

            <div class="box-footer">
                <?= Html::submitButton($orderbilling_model->isNewRecord ? 'Update Amount' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </form>
    </div><!-- /.box -->

<?php ActiveForm::end(); ?>
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