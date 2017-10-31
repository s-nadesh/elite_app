<!-- Left side column. contains the logo and sidebar -->
<?php

use yii\widgets\Menu;
?>
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
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
                ['label' => '<i class="fa fa-list"></i> <span>Order Management</span>', 'url' => ['orders/index']
                   ],
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu'>\n{items}\n</ul>\n",
        ]);
        ?>
    </section>
    <!-- /.sidebar -->
</aside>