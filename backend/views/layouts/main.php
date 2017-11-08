<?php
/* @var $this View */
/* @var $content string */

use backend\assets\ThemeAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

ThemeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-blue">
        <?php $this->beginBody() ?>
        <?php $this->beginContent('@app/views/layouts/header.php'); ?>
        <?php $this->endContent(); ?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php $this->beginContent('@app/views/layouts/aside.php'); ?>
            <?php $this->endContent(); ?>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <?= Html::encode($this->title) ?>
                    </h1>
                    <?=
                    Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Dashboard'),
                            'url' => Yii::$app->homeUrl,
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])
                    ?>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row">
                        <?= Alert::widget() ?>
                    </div>
                    <?= $content ?>
                </section>
                <!-- content -->
            </aside>
            <!-- /.right-side -->
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage(); ?>