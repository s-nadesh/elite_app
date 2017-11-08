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
        </div>
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-xs-6 ">
                    <div class="row">
                        <div class="col-sm-4"><b>Invoice Date</b></div>
                        <div class="col-sm-8">
                            <?php echo $model->invoice_date; ?>
                        </div>
                        <div class="col-sm-4"><b>Customer/Dealer</b></div>
                        <div class="col-sm-8"><?php echo $model->user->name; ?></div>
                        <div class="col-sm-4"><b>Sales Executive</b></div>
                        <div class="col-sm-8"><?php echo $model->orderedBy->name; ?></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 ">
                    <p class="lead">Order Items</p>
                    <div class="table-responsive">

                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Serial No</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price Per Unit</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
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


<?php
/* For Subcategory dropdown list */

$subcategorycallback = Yii::$app->urlManager->createUrl(['orders/getsubcategorylist']);
$productlistcallback = Yii::$app->urlManager->createUrl(['orders/getproductlist']);

$script = <<< JS
           jQuery(document).ready(function () { 
        
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