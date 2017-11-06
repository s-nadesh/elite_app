<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = 'Update Orders: ' . $model->invoice_no;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_id, 'url' => ['view', 'id' => $model->order_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<aside class="right-side">
    <section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>
    <section class="content">
                    <div class="row">
    <?= $this->render('_form', [
       'model' => $model,
                'orderby'=>$orderby,
                'users'=>$users,
                'orderbilling_model' => $orderbilling_model,
                'paid_amount' => $paid_amount,
                'pending_amount'=>$pending_amount,
                'tmodel' => $tmodel,
    ]) ?>


</div>
</section>
</aside>
