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
<aside class="right-side">
    <section class="content-header">

        <h1>Order Information</h1>
    </section>
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
                <?php foreach ($order_track as $info): ?>
                    <?php
                    if ($info->order_status_id == OrderStatus::OR_CANCELED) {
                        $responseArray = json_decode($info->value, true);
                        ?>
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Canceled Information</h3>
                            </div>

                            <div class="box-body no-padding">
                                <label class="col-sm-2 control-label">Comments</label>
                                <div class="col-sm-5"><?php echo $responseArray; ?></div>
                            </div>       

                        </div>&nbsp;
                    <?php } ?>   

                    <?php
                    if ($info->order_status_id == OrderStatus::OR_DISPATCHED) {
                        $responseArray = json_decode($info->value, true);
                        ?>
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Dispatch Information</h3>

                            </div>
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Track Id</th>
                                        <th>Date</th>
                                        <th>Courier Company Name</th>
                                        <th>Comments</th>
                                    </tr>
                                    <?php if ($responseArray['dispatch_track_id'] != NULL) { ?>
                                        <tr>
                                            <td><?php echo $responseArray['dispatch_track_id'] ?></td>
                                            <td><?php echo $info->created_at ?></td>
                                            <td><?php echo $responseArray['dispatch_courier_comapny'] ?></td>
                                            <td><?php echo $responseArray['dispatch_comment'] ?></td>
                                        </tr>
                                    <?php } else { ?>
                                        <tr>
                                            <td><?php echo 'No Results' ?></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                            <!--/.box-body--> 
                        </div>
                    <?php } ?>   
                    <!--/.box--> 
                    <div class="box">
                        <?php
                        if ($info->order_status_id == OrderStatus::OR_DELEVERED) {
                            $responseArray = json_decode($info->value, true);
                            ?>

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Delivered Information</h3>

                                </div>
                                <!--/.box-header--> 
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Received By</th>
                                            <th>Date</th>
                                            <th>Mobile No</th>
                                            <th>Address</th>

                                        </tr>
                                        <?php if ($responseArray['deliver_to'] != NULL) { ?>
                                            <tr>
                                                <td><?php echo $responseArray['deliver_to'] ?></td>
                                                <td><?php echo $info->created_at ?></td>
                                                <td><?php echo $responseArray['deliver_phone'] ?></td>
                                                <td><?php echo $responseArray['deliver_address'] ?></td>

                                            </tr>
                                        <?php } else { ?>
                                            <tr>
                                                <td><?php echo 'No Results' ?></td>
                                            </tr>
                                        <?php } ?>

                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        <?php } ?>  
                        <!-- /.box -->
                    <?php endforeach; ?>


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
</aside>