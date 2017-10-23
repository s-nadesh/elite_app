<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserTypes */

$this->title = 'Create User Types';
$this->params['breadcrumbs'][] = ['label' => 'User Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
        <!--        <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li><a href="#">Tables</a></li>
                    <li class="active">Data tables</li>
                </ol>-->
        <?php $this->params['breadcrumbs'][] = $this->title; ?>
    </section>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</aside>
