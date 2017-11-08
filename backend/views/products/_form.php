<?php

use common\models\Products;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $model Products */
/* @var $form ActiveForm */
?>

<?php
$this->registerJs(
        '$("#new_product").on("pjax:end", function() {
            $.pjax.reload({container:"#products"});  //Reload GridView
        });'
);
?>

<?php Pjax::begin(['id' => 'new_product']) ?>
    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'class' => 'form-horizontal',
                    'data-pjax' => true,
                ],
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>
<div class="box-body">
    <div class="products-form">
    <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => '--Select Type--'])->label('Category Type<span class="required-label"></span>'); ?>

<?php
                //Set selected value after validation.
                $data = [];
                if ($model->subcat_id) {
                    $data = [
                        $model->subcat_id => $model->subcat->subcat_name
                    ];
                }
                ?>
                <?=
                $form->field($model, 'subcat_id')->widget(DepDrop::classname(), [
                    'data' => $data,
                    'pluginOptions' => [
                        'initialize' => true,
                        'depends' => ['products-category_id'],
                        'placeholder' => '--Select--',
                        'url' => Url::to(['/sub-categories/getsubcategories'])
                    ]
                ]);
                ?>
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
        <div class="col-sm-0 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
        <?php Pjax::end() ?>

</div>
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
