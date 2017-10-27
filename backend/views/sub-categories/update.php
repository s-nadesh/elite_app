<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SubCategories */

$this->title = 'Update Sub Categories';
$this->params['breadcrumbs'][] = ['label' => 'Sub Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subcat_id, 'url' => ['view', 'id' => $model->subcat_id]];
$this->params['breadcrumbs'][] = 'Update';
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
                'items' => $items,
            ])
            ?>

        </div>
    </div>
</aside>
