<?php

use common\models\Products;
use common\models\StockLog;
use common\models\Users;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Products */
/* @var $stock StockLog */

$this->title = "Product View";
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Stock Log";
?>
<div class="col-md-6">
    <div class="box box-primary">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                'category.category_name',
                'subcat.subcat_name',
                'product_name',
                'min_reorder',
                'stock',
                'price_per_unit',
                'status',
                    [
                    'attribute' => 'created_at',
                    'filter' => false,
                    'format' => ['date', 'php:Y-m-d H:i:s'],
                ],
            ],
        ])
        ?>
    </div>
</div>

<div class="col-md-6">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Reorder Stock</h3>
        </div><!-- /.box-header -->
        <?php
        $form = ActiveForm::begin([
                    'id' => 'active-form',
                    'options' => [
                        'class' => 'form-horizontal',
                        'role' => "form"
                    ],
        ]);
        ?>
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-4 control-label valueleft">Current Stock</label>
                <div class="col-sm-6 valueright"><?php echo $model->stock; ?></div>
                <?php // $form->field($model, 'quantity')->textInput(['maxlength' => true])->label('Current Stock') ?>
            </div>
            <div class="clearfix"> </div>
            <div class="form-group">
                <label class="col-sm-4 control-label valueleft">Reorder Quantity</label>
                <div class="col-sm-5 valueright">
                    <?= $form->field($stock, 'adjust_quantity')->textInput(['maxlength' => true])->label(false) ?>
                </div>
            </div>
            <div class="clearfix"> </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <?= Html::submitButton('Add Stock', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <!-- /.box-footer -->
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Reorder Stock Details</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                        'attribute' => 'product_id',
                        'value' => 'product.product_name',
                        'filter' => false,
                    ],
                    'adjust_to',
                    'adjust_quantity',
                        ['attribute' => 'createUserName', 'format' => 'raw'],
                        [
                        'attribute' => 'created_at',
                        'filter' => false,
                        'format' => ['date', 'php:Y-m-d H:i:s'],
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>


