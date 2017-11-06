<?php

use common\models\Products;
use common\models\Users;
use yii\helpers\Html;
$this->title = "Dashboard";
$this->params['breadcrumbs'][] = $this->title;
$view = "View All <i class='fa fa-arrow-circle-right'></i>";
?>
<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">

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
                        <?php $products = Products::find()->count()?>
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
<!--            <div class="col-lg-3 col-xs-6">
                 small box 
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                            44
                        </h3>
                        <p>
                            Orders
                        </p>
                    </div>
                    <div class="icon">
                        <i class="ion-android-list"></i>
                    </div>
                    <?php echo Html::a($view, ['#'], ["class" => 'small-box-footer']); ?>
                </div>
            </div>-->
            <!-- ./col -->

    </section><!-- /.content -->
</aside>
<!-- /.right-side -->

