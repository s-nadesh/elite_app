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
<?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>
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
                <?=
                $form->field($model, 'subcat_id')->widget(DepDrop::classname(), [
                    'pluginOptions' => [
                        'depends' => ['carts-category_id'],
                        'placeholder' => '--Select--',
                        'url' => Url::to(['/sub-categories/getsubcategories'])
                    ]
                ]);
                ?>
            </div>
            <div class="col-md-4">
                <?=
                $form->field($model, 'product_id')->widget(DepDrop::classname(), [
                    'options' => [
                        'onchange' => '$.post( "' . Yii::$app->urlManager->createUrl('products/getproduct?id=') . '"+$(this).val(),
                        function( data ) {
                            $( "#carts-product_price" ).val( data.price_per_unit );
                        }, "json");
                '],
                    'pluginOptions' => [
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
                <?= $form->field($model, 'product_price')->textInput(['readOnly'=> true]) ?>
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
    <?= Html::submitButton($model->isNewRecord ? 'Add to cart' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
<?php Pjax::end() ?>
