<!-- Left side column. contains the logo and sidebar -->
<?php

use yii\widgets\Menu;
?>
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo Yii::$app->view->theme->baseUrl ?>/img/avatar3.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Hello, Jane</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <!-- /.search form -->
        <?php
        echo Menu::widget([
            'options' => [
                    ['class' => 'sidebar-menu'],
            ],
            'items' => [
                    ['label' => 'Dashboard', 'class' => 'fa fa-dashboard', 'url' => ['site/index']],
                    ['label' => 'User Management', 'items' => [
                            ['label' => 'Types', 'url' => ['user-types/index']],
                            ['label' => 'Users', 'url' => ['users/index']],
                    ]],
            ],
        ]);
        ?>
    </section>
    <!-- /.sidebar -->
</aside>