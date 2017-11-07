<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SubCategories */

$this->title = 'Create Sub Categories';
$this->params['breadcrumbs'][] = ['label' => 'Sub Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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
