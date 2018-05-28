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
//                             [
//                                 'header' => 'Order No',
//                                'attribute' => 'invoice_no',
//                            ],
                             [
                                 'header' => 'Order Date',
                                'attribute' => 'invoice_date',
                            ],
                             [
                                  'header' => 'Customer/Dealer',
                                 'attribute' => 'user',
                               'value' => 'user.name',
                            ],
                             [
                                 'header' => 'Products',
                               'value' => function ($model, $key, $index, $column) {
                                    $res = '';
                                   foreach ($model->orderItems as $key => $value) {
                                       $res .= $value->product_name.' ( '.$value->quantity.' ) ' . ', ';
                                   }
                                   return $res;
                                               
                                            },
                            ],
                                                   
                            [
                                  'header' => 'City',
                                 'attribute' => 'user',
                               'value' => 'user.city',
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
/* For update the status of the schedule */
$script = <<< JS
      
    jQuery(document).ready(function () { 
         $(document).keyup(function(e) {
     if (e.keyCode == 27) { 
      window.history.go(-1);
    }
});
    });
JS;
$this->registerJs($script, View::POS_END);
?>