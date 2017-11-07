<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Users */

$this->title = 'Update Users: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?php $this->params['breadcrumbs'][] = $this->title; ?>
<div class="col-md-12">
    <div class="box box-primary">

        <!--<div class="users-update">-->

        <?=
        $this->render('_form', [
            'model' => $model,
//                'model1' => $model1,
            'items' => $items,
        ])
        ?>

    </div>
</div>
