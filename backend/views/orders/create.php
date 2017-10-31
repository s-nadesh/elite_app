<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Orders */

$this->title = 'Create Orders';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<<aside class="right-side">
    <section class="content-header">

    <h1><?= Html::encode($this->title) ?></h1>
</section>
    <div class="col-md-12">
        <div class="box box-primary">
    <?= $this->render('_form', [
        'model' => $model,
        'orderby'=>$orderby,
        'users'=>$users,
        'odritem_model'=>$odritem_model,
        'categories' => $categories,
        'sub_categories' => $sub_categories,
        'products'=>$products,
          'orderbilling_model'=>$orderbilling_model,
    ]) ?>

  </div>
    </div>
</aside>
