<?php

use common\models\Carts;
use kartik\depdrop\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $model Carts */
/* @var $form ActiveForm */
?>

<?php
$this->registerJs(
        '$("#new_cart").on("pjax:end", function() {
            $.pjax.reload({container:"#carts"});  //Reload GridView
        });'
);
?>

<?php Pjax::begin(['id' => 'new_cart']) ?>
<?php $form = ActiveForm::begin([
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'options' => ['data-pjax' => true]
    ]); ?>
<div class="box-body">
    <div class="carts-form">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'user_id')->dropDownList($users, ['prompt' => '--Select--']) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'ordered_by')->dropDownList($sales_exe, ['prompt' => '--Select--']) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'category_id')->dropDownList($categories, ['prompt' => '--Select--']) ?>
            </div>
            <div class="col-md-4">
                <?php
                //Set selected value after validation.
                $data = [];
                if ($model->subcat_id) {
                    $data = [
                        $model->subcat_id => $model->product->subcat->subcat_name
                    ];
                }
                ?>
                <?=
                $form->field($model, 'subcat_id')->widget(DepDrop::classname(), [
                    'data' => $data,
                    'pluginOptions' => [
//                        'initialize' => true,
                        'depends' => ['carts-category_id'],
                        'placeholder' => '--Select--',
                        'url' => Url::to(['/sub-categories/getsubcategories'])
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-4">
                <?php
                //Set selected value after validation.
                $product_data = [];
                if ($model->product_id) {
                    $product_data = [
                        $model->product_id => $model->product->product_name
                    ];
                }
                ?>
                <?=
                $form->field($model, 'product_id')->widget(DepDrop::classname(), [
                    'options' => [
                        'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl('products/getproduct?id=') . '"+$(this).val(),
                        function( data ) {
                            $( "#carts-product_price" ).val( data.price_per_unit );
                        }, "json");
                '],
                    'data' => $product_data,
                    'pluginOptions' => [
                        'initialize' => true,
                        'depends' => ['carts-category_id', 'carts-subcat_id'],
                        'placeholder' => '--Select--',
                        'url' => Url::to(['/products/getproducts'])
                    ]
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'product_price')->textInput(['readOnly' => true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'qty')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'total_amount')->textInput() ?>
            </div>
        </div>
    </div>
</div>
<div class="box-footer">
    <?= Html::submitButton($model->isNewRecord ? 'Add to cart' : 'Update', ['id'=>'submitButton','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>
<?php
$script = <<< JS
           jQuery(document).ready(function () { 
        $('body').on('keyup','#carts-qty',function (e) {
                 var quantity = $(this).val();
                 var price = $("#carts-product_price").val();
                 var total_value = quantity * price;
                  $('#carts-total_amount').val(total_value);
             });
        
         });
JS;
$this->registerJs($script, View::POS_END);
?>