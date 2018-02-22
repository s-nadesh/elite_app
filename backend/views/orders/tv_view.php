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

$this->title = 'Tv View';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="services-index">
    <div class="row">
       
        <div class="col-lg-12 col-md-12">&nbsp;</div>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
<div class="col-md-12">
            <div class="pull-right">
                <?= Html::a('Back', ['orders/index'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
//                        'filterModel' => $searchModel,
                        'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                             [
                                 'header' => 'Order No',
                                'attribute' => 'invoice_no',
                            ],
                             [
                                 'header' => 'Order Date',
                                'attribute' => 'invoice_date',
                            ],
                             [
                                 'header' => 'Products',
                               'value' => function ($model, $key, $index, $column) {
                                   foreach ($model->orderItems as $key => $value) {
                                        $array[]= $value->product_name.' ( '.$value->quantity.' ) ';
                                   }
                                   return join(", ",$array);
                                               
                                            },
                            ],
                                
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
