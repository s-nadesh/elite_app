<?php
/* @var $this View */
/* @var $form ActiveForm */
/* @var $model LoginForm */

use common\models\LoginForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\View;
?>
<div class="form-box" id="login-box">
    <div class="header">Sign In</div>
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'method' => 'post']); ?>

    <div class="body bg-gray">
        <div class="form-group">
            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username', 'class' => 'form-control'])->label(false); ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'class' => 'form-control'])->label(false); ?>
        </div>
        <div class="form-group">
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
        </div>
    </div>

    <div class="footer">
        <?= Html::submitButton('Sign in', ['class' => 'btn bg-olive btn-block', 'name' => 'login-button']) ?>
        <p><a href="#">I forgot my password</a></p>
    </div>
    <?php ActiveForm::end(); ?>
</div>