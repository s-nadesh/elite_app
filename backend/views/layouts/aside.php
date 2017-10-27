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
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php
        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'encodeLabels' => false,
            'activateParents' => true,
            'activateItems' => true,
            'items' => [
                    ['label' => '<i class="fa fa-dashboard"></i> <span>Dashboard</span>', 'url' => ['site/index']],
                    ['label' => '<i class="fa fa-users"></i> <span>User Management</span><i class="fa fa-angle-left pull-right"></i>',
                    'url' => ['#'],
                    'options' => ['class' => 'treeview'],
                    'items' => [
                            ['label' => '<i class="fa fa-circle-o"></i>Types', 'url' => ['user-types/index']],
                            ['label' => '<i class="fa fa-circle-o"></i>Users', 'url' => ['users/index']],
                    ]],
                    ['label' => '<i class="fa fa-th"></i> <span>Product Management</span><i class="fa fa-angle-left pull-right"></i>',
                    'url' => ['#'],
                    'options' => ['class' => 'treeview'],
                    'items' => [
                            ['label' => '<i class="fa fa-circle-o"></i>Category', 'url' => ['categories/index']],
                            ['label' => '<i class="fa fa-circle-o"></i>Sub-Category', 'url' => ['sub-categories/index']],
                            ['label' => '<i class="fa fa-circle-o"></i>Product', 'url' => ['products/index']],
                    ]],
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
        ]);
        ?>
    </section>
    <!-- /.sidebar -->
</aside>