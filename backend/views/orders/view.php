<?php

use common\models\OrderItems;
use common\models\Orders;
use common\models\OrderStatus;
use common\models\OrderTrack;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model ElOrder */

$this->title = "Order id : " . $model->order_id;
?>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">User Payment Details</h3>
                        <h3 class="box-title pull-right">
                            <?php
                            $url = Url::toRoute('orders/update?id=' . $model->order_id);
                            if ($model->order_status_id != OrderStatus::OR_CANCELED && $model->order_status_id != OrderStatus::OR_COMPLETED) {
                                echo Html::a('<span title="Edit" class="glyphicon glyphicon-pencil"></span>', $url);
                            }
                            ?>
                        </h3>
                    </div>
                    <div  class="row">   
                        <div class="col-sm-4 "><b>User name</b></div>
                        <div class="col-sm-7"><?php echo $model->user->name; ?></div>

                        <div class="col-sm-4 "><b>Total Amount</b></div>
                        <div  class="col-sm-7"><?php echo $model->total_amount; ?></div>

                        <div class="col-sm-4 "><b>Invoice NUmber</b></div>
                        <div class="col-sm-7"><?php echo $model->invoice_no; ?></div>

                        <div class="col-sm-4"><b>Status</b></div>

                        <?php
                        if ($model->order_status_id == OrderStatus::OR_CANCELED) {
                            $model->order_status_id = '<span class="label label-danger">Cancelled</span>';
                        } else if ($model->order_status_id == OrderStatus::OR_NEW) {
                            $model->order_status_id = '<span class="label label-info">New Order</span>';
                        } else if ($model->order_status_id == OrderStatus::OR_INPROGRESS) {
                            $model->order_status_id = '<span class="label label-warning">InProgress</span>';
                        } else if ($model->order_status_id == OrderStatus::OR_COMPLETED) {
                            $model->order_status_id = '<span class="label label-success">Completed</span>';
                        } else if ($model->order_status_id == OrderStatus::OR_DISPATCHED) {
                            $model->order_status_id = '<span class="label label-default">Dispatched</span>';
                        } else if ($model->order_status_id == OrderStatus::OR_DELEVERED) {
                            $model->order_status_id = '<span class="label label-success">Delivered</span>';
                        }
                        ?>

                        <div class="col-sm-7"><?php echo $model->order_status_id ?>  </div>
                    </div>&nbsp;
                </div>


                <?php
                $order_items = OrderItems::find()->where('status=:sid and order_id=:id', ['sid' => 1, 'id' => $model->order_id])->orderBy(['created_at' => SORT_DESC])->all();
                ?>
                <div class="box">


                    <div class="box-header">

                        <h3 class="box-title">Order Details</h3>

                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="table1">
                            <tr>
                                <th>Index</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price Per Unit</th>
                                <th>Total Amount</th>

                            </tr>
                            <tbody id="mytbody">
                                <?php
                                if (!$model->isNewRecord) {
                                    $i = 1;
                                    foreach ($order_items as $info):
                                        ?>
                                        <tr>
                                            <td><?php echo $i ?></td>
                                            <td><?php echo $info->category_name ?></td>
                                            <td><?php echo $info->subcat_name ?></td>
                                            <td><?php echo $info->product_name ?></td>
                                            <td><?php echo $info->quantity ?></td>
                                            <td><?php echo $info->price ?></td>
                                            <td><?php echo $info->total ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    endforeach;
                                    ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                </div>&nbsp;

                <?php $order_track = OrderTrack::find()->where('status=:sid and order_id=:id ', ['sid' => 1, 'id' => $model->order_id])->orderBy(['created_at' => SORT_DESC])->all();
                ?>
                <div class="box">


                    <div class="box-header">

                        <h3 class="box-title">Order Details</h3>

                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover" id="table1">
                            <tr>
                                <th>Index</th>
                                <th>Order Track</th>
                                <th>Date Time</th>
                                <th>Value</th>

                            </tr>
                            <tbody id="mytbody">
                                <?php
                                    $i = 1;
                                    foreach ($order_track as $info):
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

                </div>&nbsp;

                <div class="box">

                    <div class="box-header">
                        <h3 class="box-title">Payment Details</h3>
                    </div>
                    <div class="box-body no-padding">
                        <?=
                        GridView::widget([
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
