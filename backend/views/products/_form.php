<?php

use common\models\Products;
use common\models\SubCategories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Products */
/* @var $form ActiveForm */
?>

<div class="box-body">

    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>

    <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => '--Select Type--'])->label('Category Type<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'subcat_id')->dropDownList(ArrayHelper::map(SubCategories::find()->andWhere(' status=1')->all(), 'subcat_id', 'subcat_name'), ['prompt' => '--Select Subcategory--'])->label('Sub Category Type<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true])->label('Product Name<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'min_reorder')->textInput() ?>

    <?= $form->field($model, 'stock')->textInput(['maxlength' => true])->label('Stock<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'price_per_unit')->textInput(['maxlength' => true])->label('Price-per-unit<span class="required-label"></span>'); ?>
    <?php
    if ($model->isNewRecord) {
        $model->status = true;
    }
    ?>
    <?= $form->field($model, 'status')->checkbox(['label' => ('Active')])->label('Status') ?>

    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
/* For Subcategory dropdown list */
$subcatcallback = Yii::$app->urlManager->createUrl(['/products/getsubcatlist']);

$script = <<< JS
           jQuery(document).ready(function () { 
        
        var catid  = $('#products-category_id').val();
      
        $('#products-category_id').on('change', function() {
            var catid     = $(this).val(); 
            if(catid!=""){  
            subcatlist(catid);  
            } else{ 
            subcatlist('0'); 
            }
            });
          function subcatlist(catid){
            $.ajax({
                url  : "{$subcatcallback}",
                type : "POST",                   
                data: {
                  id: catid,                       
                },
                success: function(data1) {
                  $("#products-subcat_id").html(data1);
                }
           });  
        }
        
         });
JS;
$this->registerJs($script, View::POS_END);
?>
