<?php

use common\models\OrderItems;
use common\models\Orders;
use common\models\OrderStatus;
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
                'template' => "{label}<div class=\"col-sm-12\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
                ]
);
?>
<?php // print_r($model);exit;?>

<?php if ($model->isNewRecord) { ?>
    <div class="col-lg-5 col-md-5 ">
        <?= $form->field($model, 'user_id')->dropDownList($users, ['prompt' => '--Select User--'])->label('Dealers'); ?>
    </div>
    <div class="col-lg-5 col-md-5 ">
        <?= $form->field($model, 'ordered_by')->dropDownList($orderby, ['prompt' => '--Select User--'])->label('Sales Executive'); ?>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="row">
            <div class="panel panel-info advance_search">

        <!--<section class="content">-->
                <div class="row">
                    <div class="col-lg-4 col-md-4 ">
                        <label><span class="required-label"></span>Category</label>
                        <?= $form->field($odritem_model, 'category_id')->dropDownList($categories, ['prompt' => '--Select Category--'])->label(false); ?>
                    </div>
                    <div class="col-lg-4 col-md-4 ">
                        <label><span class="required-label"></span>Sub Category</label>
                        <?= $form->field($odritem_model, 'subcat_id')->dropDownList($sub_categories, ['prompt' => '--Select Sub Category--'])->label(false); ?>
                    </div>
                    <div class="col-lg-4 col-md-4 ">
                        <label><span class="required-label"></span>Product</label>
                        <?= $form->field($odritem_model, 'product_id')->dropDownList($products, ['prompt' => '--Select Product--'])->label(false); ?>
                    </div>
                    <div class="col-lg-4 col-md-4 ">
                        <label></span>Price</label>
                        <?= $form->field($odritem_model, 'price')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                    <div class="col-lg-4 col-md-4 ">
                        <label>Quantity</label>
                        <?= $form->field($odritem_model, 'quantity')->textInput(['maxlength' => true])->label(false) ?>
                    </div>
                    <?php // $form->field($odritem_model, 'quantity')->dropDownList([ 'P' => 'P', 'C' => 'C', 'PC' => 'PC', ], ['prompt' => '']) ?>
                    <div class="col-lg-4 col-md-4 ">
                        <label>Total</label>
                        <?= $form->field($odritem_model, 'total')->textInput(['maxlength' => true])->label(false) ?>
                    </div>         

                    <div class="col-lg-12 text-center">
                        <div class="form-group">
                            <input type="button" class="btn btn-primary add-row" id="btnAddProfile" value="Add">
                        </div>
                    </div>
                </div>
                <!--</section>-->
            </div>
        </div>
    </div>
<?php } ?>
<?php if (!$model->isNewRecord) { ?>
    <div class="form-group">
        <label class="col-sm-2 control-label valueleft">Invoice Number</label>
        <div class="col-sm-5 valueright">
            <?= $form->field($model, 'invoice_no')->textInput(['maxlength' => true])->label(false) ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label valueleft">Customer/Dealer</label>
        <div class="col-sm-5 valueright"><?php echo $model->user->name; ?></div>
    </div>
<?php } ?>

<?php
$order_models = OrderItems::find()->where('status=:sid and order_id=:id', ['sid' => 1, 'id' => $model->order_id])->orderBy(['created_at' => SORT_DESC])->all();
//        print_r($order_models);exit;
?>
<div class="col-md-12 ">
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
                        foreach ($order_models as $info):
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
<?php if ($model->isNewRecord) { ?>
    <div class="col-lg-4 col-md-4 ">
        <label>Total Amount</label>
        <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true])->label(false) ?>
    </div>
<?php } else { ?>
    <div class="form-group">
        <label class="col-sm-2 control-label valueleft">Total Amount</label>
        <div class="col-sm-5 valueright">
            <?= $form->field($model, 'total_amount')->textInput(['readonly' => !$model->isNewRecord])->label(false) ?>
        </div>
    </div>
<?php } ?>

<div id="error_receivedamount">            
    <div class="alert alert-danger alert-dismissable">                          
    </div>
</div>

<div class="clearfix"> </div>    
<div class="form-group">
    <label class="col-sm-2 control-label valueleft">Current Received Amount</label>
    <div class="col-sm-5 valueright">
        <?= $form->field($orderbilling_model, 'paid_amount')->textInput(['maxlength' => true])->label(false) ?>
    </div>
</div>


<div class="clearfix"> </div>  
<div class="form-group">
    <label class="col-sm-2 control-label valueleft">Status</label>
    <div class="col-sm-5 valueright">
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


<div class="col-lg-12">
    <?= Html::submitButton($model->isNewRecord ? 'Place order' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
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
                 var received_amount = $(this).val();
              
                 var pending_amount = $("#orders-total_amount").val();
                
                 if(parseFloat($(this).val()) > parseFloat($("#orders-total_amount").val())){
                
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
      
        
       
         });
JS;
$this->registerJs($script, View::POS_END);
?>