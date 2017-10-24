<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserTypes */

$this->title = 'Create User Types';
$this->params['breadcrumbs'][] = ['label' => 'User Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<aside class="right-side">
    <section class="content-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </section>
    <div class="col-md-12">
        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>
    </div>
</aside>
