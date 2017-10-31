<?php

use common\models\Orders;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Orders */
/* @var $form ActiveForm */
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
                                    $productname = ElProducts::find()->where('product_id=:productid', ['productid' => $info->product_id])->one();
                                    ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo $productname->cate->cat_name ?></td>
                                        <td><?php echo $productname->subCate->sub_catename ?></td>
                                        <td><?php echo $productname->product_name ?></td>
                                        <td><?php echo $info->quantity ?></td>
                                        <td><?php echo $info->price_per_unit ?></td>
                                        <td><?php echo $info->total_amt ?></td>
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
<div class="col-lg-4 col-md-4 ">
    <label>Total Amount</label>
    <?= $form->field($model, 'total_amount')->textInput(['maxlength' => true])->label(false) ?>
</div>
<div class="clearfix"> </div>    
<div class="col-lg-4 col-md-4 ">
    <label>Current Received Amount</label>
    <?= $form->field($orderbilling_model, 'paid_amount')->textInput(['maxlength' => true])->label(false) ?>
</div>


<div class="col-lg-12">
    <?= Html::submitButton($model->isNewRecord ? 'Place order' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div></div>
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
                
                
      //Get Product list----
                
                 var subcatid  = $('#orderitems-subcat_id').val();
              
                $('#orderitems-subcat_id').on('change', function() {
                
                    var subcatid  = $(this).val(); 
                 alert(subcatid);
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