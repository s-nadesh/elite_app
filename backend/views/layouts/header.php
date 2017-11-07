<?php

use yii\helpers\Html;
?>
<header class="header">
    <?php echo Html::a('Elite', ['/site/index'], ['class' => 'logo']); ?>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?= Yii::$app->user->identity->username; ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="<?php echo Yii::$app->view->theme->baseUrl ?>/img/avatar3.png" class="img-circle" alt="User Image" />
                            <p>
                                <?= Yii::$app->user->identity->username; ?>
                                <small> <?= Yii::$app->user->identity->email; ?></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
<!--                        <li class="user-body">
                            <div class="col-xs-12 text-center">
                                <?php // echo Html::a('Change Password', ['/site/changepassword']); ?>
                            </div>
                        </li>-->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php echo Html::a('Change Password', ['/site/changepassword'], ['class'=> 'btn btn-default btn-flat']); ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('Sign out', ['/site/logout'], ['class' => 'btn btn-default btn-flat']); ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>