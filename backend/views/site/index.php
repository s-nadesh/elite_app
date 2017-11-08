<?php

use common\models\Orders;
use common\models\Products;
use common\models\Users;
use yii\helpers\Html;

$this->title = "Dashboard";
$this->params['breadcrumbs'][] = $this->title;
$view = "View All <i class='fa fa-arrow-circle-right'></i>";
?>
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <?php $users = Users::find()->count(); ?>
                <h3>
                    <?php echo $users; ?>
                </h3>
                <p>
                    Users
                </p>
            </div>
            <div class="icon">
                <i class="ion-android-people"></i>
            </div>
            <?php echo Html::a($view, ['/users'], ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <?php $products = Products::find()->count() ?>
                <h3>
                    <?= $products ?>
                </h3>
                <p>
                    Products
                </p>
            </div>
            <div class="icon">
                <i class="ion-bag"></i>
            </div>
            <?php echo Html::a($view, ['/products'], ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <?php $orders = Orders::find()->count(); ?>
                <h3>
                    <?= $orders; ?>
                </h3>
                <p>
                    Orders
                </p>
            </div>
            <div class="icon">
                <i class="ion-android-menu"></i>
            </div>
            <?php echo Html::a($view, ['/orders'], ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->
</div>