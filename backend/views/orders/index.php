<?php

use yii\bootstrap\Modal;
use common\models\OrdersSearch;
use common\models\OrderStatus;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $searchModel OrdersSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
$new_order = ArrayHelper::map(OrderStatus::find()->where('status=:id and status_position_id!=:id1', ['id' => 1, 'id1' => 3])->all(), 'status_position_id', 'status_name');

$inprogress = ArrayHelper::map(OrderStatus::find()->where('status=:id and status_position_id!=:id1 and status_position_id!=:id2', ['id' => 1, 'id1' => 3, 'id2' => 1])->all(), 'status_position_id', 'status_name');


$dispatch = ArrayHelper::map(OrderStatus::find()->where('status=:id and status_position_id=:id1 or status_position_id=:id2', ['id' => 1, 'id1' => 4, 'id2' => 5])->all(), 'status_position_id', 'status_name');
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </section>
    <section class="content">
        <div class="services-index">
            <div class="col-md-12">
                <div class="row">
                    <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

                    <div class="pull-right">
                        <?= Html::a('Create Order', ['carts/index'], ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">&nbsp;</div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <?php echo $this->render('_search1', ['model' => $searchModel]); ?>

                            <?=
                            GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                        'header' => 'Invoice No',
                                        'attribute' => 'invoice_no',
                                        'contentOptions' => ['style' => 'width:150px; white-space: normal;'],
                                    ],
                                        [
                                        'header' => 'Invoice Date',
                                        'attribute' => 'invoice_date',
                                        'contentOptions' => ['style' => 'width:150px; white-space: normal;'],
                                    ],
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
                                        'header' => 'Total Amount',
                                        'attribute' => 'total_amount',
                                        'contentOptions' => ['style' => 'width:150px; white-space: normal;'],
                                    ],
                                        [
                                        'header' => 'Status',
                                        'attribute' => 'order_status_id',
                                        'filter' => false,
                                        'format' => 'raw',
                                        'contentOptions' => ['style' => 'width:100px; white-space: normal;'],
                                        'value' => function ($model) {
                                            if ($model->order_id) {
                                                if ($model->order_status_id == "1") {
                                                    $url = Url::toRoute('orders/update?id=' . $model->order_id);
                                                    $sc_stat = Html::a('<span title="Update" class="label label-info">New Order</span>', $url);
                                                } else if ($model->order_status_id == "6") {
                                                    $url = Url::toRoute('orders/view?id=' . $model->order_id);
                                                    $sc_stat = Html::a('<span title="Go to view" class="label label-danger">Cancelled</span>', $url);
                                                } else if ($model->order_status_id == "2") {
                                                    $url = Url::toRoute('orders/update?id=' . $model->order_id);
                                                    $sc_stat = Html::a('<span title="Update" class="label label-warning">In Progress</span>', $url);
                                                } else if ($model->order_status_id == "3") {
                                                    $url = Url::toRoute('orders/view?id=' . $model->order_id);
                                                    $sc_stat = Html::a('<span title="Go to view" class="label label-success">Completed</span>', $url);
                                                } else if ($model->order_status_id == "4") {
                                                    $url = Url::toRoute('orders/update?id=' . $model->order_id);
                                                    $sc_stat = Html::a('<span title="Update" class="label label-default"> Dispatched</span>', $url);
                                                } else if ($model->order_status_id == "5") {
                                                    $url = Url::toRoute('orders/update?id=' . $model->order_id);
                                                    $sc_stat = Html::a('<span title="Update" class="label label-success">Delivered</span>', $url);
                                                }

                                                return $sc_stat;
                                            }
                                        },
// 'filter' => Html::activeDropDownlist($searchModel, 'order_status_id', ["6"=>"Cancel","1"=>"New Order","2" => 'In Progress',"3"=>"Completed", "4" => "Dispatch", "5" => "Delivered"], ['class' => 'form-control', 'id' => null, 'prompt' => 'All']),
                                    ],
                                        [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}&nbsp;&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{status}&nbsp;&nbsp;{billing}',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                if ($model->order_status_id != 3 && $model->order_status_id != 6) {
                                                    $url = Url::toRoute('orders/update?id=' . $model->order_id);
                                                    return Html::a('<span title="Update" class="glyphicon glyphicon-pencil"></span>', $url);
                                                }
                                            },
                                            'view' => function ($url, $model) {
                                                $url = Url::toRoute('orders/view?id=' . $model->order_id);
                                                return Html::a('<span title="View Order Information" class="glyphicon glyphicon-eye-open"></span>', $url);
                                            },
                                            'delete' => function($url, $model) {
                                                if ($model->order_status_id == 1 || $model->order_status_id == 6) {
                                                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['orders/delete', 'id' => $model->order_id], [
                                                                'class' => '',
                                                                'data' => [
                                                                    'confirm' => 'Are you sure you want to delete this order?',
                                                                    'method' => 'post',
                                                                ],
                                                    ]);
                                                }
                                            },
                                            'status' => function ($url, $model) {
                                                $url = Url::toRoute('orders/status?id=' . $model->order_id);
                                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['#'], ['class' => 'modelButton', 'title' => 'Change status', 'data-url' => $url]
                                                );
                                            },
                                                     'billing' => function ($url, $model) {
                                                $url = Url::toRoute('orders/billing?id=' . $model->order_id);
                                                return Html::a('<span class="glyphicon glyphicon-usd"></span>', ['#'], ['class' => 'bmodelButton', 'title' => 'Update Payment', 'data-url' => $url]
                                                );
                                            },
                                        ],
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>
<?php
Modal::begin([
    'header' => '<h4>Change Status</h4>',
    'id' => 'changeStatus',
    'size' => 'model-lg',
]);

echo "<div id='changeStatusContent'></div>";

Modal::end();
?>
<?php
Modal::begin([
    'header' => '<h4>Update Payment</h4>',
    'id' => 'changePayment',
    'size' => 'model-lg',
]);

echo "<div id='changePaymentContent'></div>";

Modal::end();
?>
<?php
$script = <<< JS
        jQuery(document).ready(function () { 
        
            $('.modelButton').click(function(e){
                e.preventDefault();
                $('#changeStatus').modal('show')
                    .find('#changeStatusContent')
                    .load($(this).data('url'));
            });
        
         $('.bmodelButton').click(function(e){
                e.preventDefault();
                $('#changePayment').modal('show')
                    .find('#changePaymentContent')
                    .load($(this).data('url'));
            });
        
        });
        
JS;
$this->registerJs($script, View::POS_END);
?>