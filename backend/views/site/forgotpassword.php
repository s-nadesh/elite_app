<?php
use common\models\Logins;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<div class="form-box" id="login-box">
     <div class="header">Forgot Password</div>
        <?php $form = ActiveForm::begin(); ?>
 <div class="body bg-gray">
         <div class="form-group">
        <?= $form->field($model, 'email') ?>
            </div>
            
       <div class="footer"> 
  <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

    </div>