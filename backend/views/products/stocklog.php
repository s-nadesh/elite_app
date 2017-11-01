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

$this->title = "Product ID : " . $model->product_id;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = "Stock Log";
?>
<aside class="right-side">
    <section class="content">
        <div class="products-view">

            <h3><?= Html::encode($this->title) ?></h3>

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
                    'created_at:datetime',
                ],
            ])
            ?>

            <?php
            $form = ActiveForm::begin([
                        'id' => 'active-form',
                        'options' => [
                            'class' => 'form-horizontal'
                        ],
                        'fieldConfig' => [
                            'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
            ]);
            ?>
            <form class="form-horizontal">
                <div class="box-body">

                    <div class="form-group">
                        <div class="col-sm-10">
                            <label class="col-sm-2 control-label">Current Stock</label>
                            <div class="col-sm-5"><?php echo $model->stock; ?></div>
                            <?php // $form->field($model, 'quantity')->textInput(['maxlength' => true])->label('Current Stock') ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <?= $form->field($stock, 'adjust_quantity')->textInput(['maxlength' => true])->label('Reorder Quatity') ?>
                        </div>
                    </div>

                    <!-- /.box-body -->
                    <div class="form-group ">
                        <div class="col-sm-4 text-center">
                            <?= Html::submitButton('Add Stock', ['class' => 'btn btn-primary']) ?>
                        </div></div> </div>
                <!-- /.box-footer -->
            </form>
            <?php ActiveForm::end(); ?>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Product Stock Log</h3>
                </div>
                <div class="box-body no-padding">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                'attribute' => 'product_id',
                                'value' => 'product.product_name',
                                'filter' => Html::activeDropDownlist($searchModel, 'product_id', ArrayHelper::map(Products::find()->where('status=:id', ['id' => 1])->all(), 'product_id', 'product_name'), ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                            ],
                            'adjust_to',
                            'adjust_quantity',
                                ['attribute' => 'createUserName', 'format' => 'raw'],
//                                [
//                                'attribute' => 'createUserName',
//                                'value' => 'createUser.name',
//                                'filter' => Html::activeDropDownlist($searchModel, 'createUserName', ArrayHelper::map(Users::find()->where('status=:id', ['id' => 1])->all(), 'user_id', 'name'), ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
//                                'format' => 'raw',
//                            ],
                            'created_at:datetime',
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </section>
</aside>
