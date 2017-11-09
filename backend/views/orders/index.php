<?php

use common\models\Orders;
use common\models\OrdersSearch;
use common\models\OrderStatus;
use yii\bootstrap\Modal;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel OrdersSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="services-index">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <?= Html::a('Create Order', ['carts/index'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                            'invoice_no',
                            'invoice_date',
                                [
                                'attribute' => 'user',
                                'value' => 'user.name',
                            ],
                                [
                                'attribute' => 'ordered_by_name',
                                'value' => 'orderedBy.name',
                            ],
                            'total_amount',
                                [
                                'class' => 'backend\components\OrderstatusColumn',
                                'attribute' => 'order_status_id',
                            ],
                                [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view}&nbsp;&nbsp;{status}&nbsp;&nbsp;{billing}',
                                'buttons' => [
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
                                'visibleButtons' => [
                                    'status' => function($model, $key, $index) {
                                        if ($model->order_status_id != OrderStatus::OR_COMPLETED && $model->order_status_id != OrderStatus::OR_CANCELED && $model->order_status_id != OrderStatus::OR_DELEVERED) {
                                            return true;
                                        }
                                    },
                                    'billing' => function($model, $key, $index) {
                                        if ($model->payment_status != Orders::OR_PAYMENT_C && $model->order_status_id != OrderStatus::OR_CANCELED) {
                                            return true;
                                        }
                                    },
//                                    'update' => function($model, $key, $index) {
//                                        if ($model->order_status_id != OrderStatus::OR_COMPLETED && $model->order_status_id != OrderStatus::OR_CANCELED) {
//                                            return true;
//                                        }
//                                    },
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

<?php
Modal::begin([
    'header' => '<h4>Change Status</h4>',
    'id' => 'changeStatus',
    'size' => 'model-lg',
]);

echo "<div id='changeStatusContent'></div>";

Modal::end();

Modal::begin([
    'header' => '<h4>Update Payment</h4>',
    'id' => 'changePayment',
    'size' => 'model-lg',
]);

echo "<div id='changePaymentContent'></div>";

Modal::end();

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