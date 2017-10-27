<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Products */

$this->title = 'Create Product';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </section>
    <div class="col-md-12">
        <div class="box box-primary">
            <?=
            $this->render('_form', [
                'model' => $model,
                'categories' => $categories,
                'sub_categories' => $sub_categories,
            ])
            ?>

        </div>
    </div>
</aside>
