<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserTypes */

$this->title = 'Create User Types';
$this->params['breadcrumbs'][] = ['label' => 'User Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
