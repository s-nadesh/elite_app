<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model common\models\Users */

$this->title = 'Create Users';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="box box-primary">
        <?=
        $this->render('_form', [
            'model' => $model,
//                'model1' => $model1,
            'items' => $items,
        ])
        ?>
    </div>
</div>
