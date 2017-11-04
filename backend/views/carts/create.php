<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Carts */

$this->title = 'Create Carts';
$this->params['breadcrumbs'][] = ['label' => 'Carts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="carts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
