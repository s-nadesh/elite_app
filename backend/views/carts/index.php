<?php

use common\models\Carts;
use common\models\CartSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel CartSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Carts';
$this->params['breadcrumbs'][] = $this->title;
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </section>
    <section class="content">
        <div class="services-index">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <!-- Render create form -->    
                        <?=
                        $this->render('_form', [
                            'model' => $model,
                            'users' => $users,
                            'sales_exe' => $sales_exe,
                            'categories' => $categories,
                        ])
                        ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="carts-index">
                                <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

                                <?php Pjax::begin(['id' => 'carts']); ?>    
                                <?=
                                GridView::widget([
                                    'dataProvider' => $dataProvider,
//                                    'filterModel' => $searchModel,
                                    'showFooter' => true,
                                    'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
//                                        'sessionid',
                                        [
                                            'attribute' => 'user_id',
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->user->name;
                                            },
                                        ],
                                            [
                                            'attribute' => 'ordered_by',
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->orderedBy->name;
                                            },
                                        ],
                                            [
                                            'attribute' => 'product_id',
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->product->product_name;
                                            },
                                        ],
                                        'qty',
                                            [
                                            'attribute' => 'product_price',
                                            'value' => function ($model, $key, $index, $column) {
                                                return $model->product->price_per_unit;
                                            },
                                        ],
                                            [
                                            'attribute' => 'total_amount',
                                            'footer' => Carts::getTotalAmount($dataProvider->models),
                                            'value' => function ($model, $key, $index, $column) {
                                                return ($model->product->price_per_unit * $model->qty);
                                            },
                                        ],
                                        // 'status',
                                        // 'created_at',
                                        // 'updated_at',
                                        // 'created_by',
                                        // 'updated_by',
                                        // 'deleted_at',
                                        ['class' => 'yii\grid\ActionColumn',
                                            'template' => '{delete}'
                                        ],
                                    ],
                                ]);
                                ?>
                                <?php Pjax::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-offset-9 col-md-3">
                    <?= Html::a('Place Order', ['orders/placeorder'], ['class' => 'btn btn-info pull-right']) ?>
                </div>
            </div>
        </div>
    </section>
</aside>