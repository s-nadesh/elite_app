<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserTypes */

$this->title = 'Update User Types';
$this->params['breadcrumbs'][] = ['label' => 'User Types', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->user_type_id, 'url' => ['view', 'id' => $model->user_type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-12">
    <div class="box box-primary">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>