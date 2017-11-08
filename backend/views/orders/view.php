<?php

use common\models\OrderStatus;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model ElOrder */

$this->title = "Order View";

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">

                <div class="row">
                    <div class="col-xs-6 ">
                        <p class="lead">User Details 
                            <?php
                            $url = Url::toRoute('orders/update?id=' . $model->order_id);
                            if ($model->order_status_id != OrderStatus::OR_CANCELED && $model->order_status_id != OrderStatus::OR_COMPLETED) {
                                echo Html::a('<span title="Edit" class="glyphicon glyphicon-pencil"></span>', $url);
                            }
                            ?>
                        </p>

                        <div  class="row">   
                            <div class="col-sm-4 "><b>User name</b></div>
                            <div class="col-sm-7"><?php echo $model->user->name; ?></div>

                            <div class="col-sm-4 "><b>Total Amount</b></div>
                            <div  class="col-sm-7"><?php echo $model->total_amount; ?></div>

                            <div class="col-sm-4 "><b>Invoice #</b></div>
                            <div class="col-sm-7"><?php echo $model->invoice_no; ?></div>

                            <div class="col-sm-4"><b>Status</b></div>
                            <div class="col-sm-7">
                                <?php
                                if ($model->order_status_id == OrderStatus::OR_CANCELED) {
                                    echo '<span class="label label-danger">Cancelled</span>';
                                } else if ($model->order_status_id == OrderStatus::OR_NEW) {
                                    echo '<span class="label label-info">New Order</span>';
                                } else if ($model->order_status_id == OrderStatus::OR_INPROGRESS) {
                                    echo '<span class="label label-warning">InProgress</span>';
                                } else if ($model->order_status_id == OrderStatus::OR_COMPLETED) {
                                    echo '<span class="label label-success">Completed</span>';
                                } else if ($model->order_status_id == OrderStatus::OR_DISPATCHED) {
                                    echo '<span class="label label-default">Dispatched</span>';
                                } else if ($model->order_status_id == OrderStatus::OR_DELEVERED) {
                                    echo '<span class="label label-success">Delivered</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

              
                <div class="row">
                    <div class="col-xs-12 ">
                        <p class="lead">Order Items</p>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Index</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price Per Unit</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="mytbody">
                                    <?php
                                        $i = 1;
                                        foreach ($model->orderItems as $info):
                                            ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo $info->category->category_name ?></td>
                                                <td><?php echo $info->subcat->subcat_name ?></td>
                                                <td><?php echo $info->product->product_name ?></td>
                                                <td><?php echo $info->quantity ?></td>
                                                <td><?php echo $info->price ?></td>
                                                <td><?php echo $info->total ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                        endforeach;
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 ">
                        <p class="lead">Order Track</p>
                        <div class="table-responsive">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Index</th>
                                        <th>Order Track</th>
                                        <th>Date Time</th>
                                        <th>Value</th>

                                    </tr>
                                </thead>
                                <tbody id="mytbody">
                                    <?php
                                    $i = 1;
                                    foreach ($model->orderTrack as $info):
                                        $responseArray = json_decode($info->value, true);
                                        ?>
                                        <tr>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $info->orderStatusname->status_name ?></td>
                                            <td><?php echo date('Y-m-d H:i:s', $info->created_at); ?></td>
                                            <td><?php
                                                if ($info->order_status_id == OrderStatus::OR_DISPATCHED) {
                                                    echo 'Track Id - ' . $responseArray['dispatch_track_id'] . '</br> ';
                                                    echo 'Courier Company - ' . $responseArray['dispatch_courier_comapny'] . '</br> ';
                                                    echo 'Comments - ' . $responseArray['dispatch_comment'];
                                                } elseif ($info->order_status_id == OrderStatus::OR_DELEVERED) {
                                                    echo 'Delivered To - ' . $responseArray['deliver_to'] . '</br> ';
                                                    echo 'Phone - ' . $responseArray['deliver_phone'] . '</br> ';
                                                    echo 'Address - ' . $responseArray['deliver_address'];
                                                } elseif ($info->order_status_id == OrderStatus::OR_CANCELED) {
                                                    echo 'Reason for cancelled - ' . $responseArray['cancel_comment'];
                                                } else {
                                                    echo '-';
                                                }
                                                ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    endforeach;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xs-12 ">
                        <p class="lead">Payment Details</p>
                        <div class="table-responsive">
                            <?=
                            GridView::widget([
                                'tableOptions' => [
                                    'class' => 'table table-striped',
                                ],
                                'dataProvider' => $dataProvider,
//                              'filterModel' => $searchModel,
                                'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                    'paid_amount',
                                        [
                                        'attribute' => 'ordered_by',
                                        'value' => function ($model, $key, $index, $column) {
                                            return $model->order->orderedBy->name;
                                        },
                                    ],
                                        [
                                        'attribute' => 'created_at',
                                        'filter' => false,
                                        'format' => ['date', 'php:Y-m-d H:i:s'],
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
