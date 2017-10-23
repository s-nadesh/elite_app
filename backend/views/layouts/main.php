<?php
/* @var $this View */
/* @var $content string */

use backend\assets\ThemeAsset;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\web\View;

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
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
