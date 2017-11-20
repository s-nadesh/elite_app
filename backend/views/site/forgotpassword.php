<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Forgot Password';
?>

<div class="form-box" id="login-box">
    <div class="header">Forgot Password</div>
    <?php $form = ActiveForm::begin(); ?>
    <div class="body bg-gray">
        <div class="form-group">
            <?= $form->field($model, 'email') ?>
        </div>
    </div>
    <div class="footer"> 
        <?= Html::submitButton('Submit', ['class' => 'btn bg-olive btn-block']) ?>
        <?php echo Html::a('Login', array('site/login')); ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>