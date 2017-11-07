<?php

use common\models\Logins;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Logins */
/* @var $form ActiveForm */
$this->title = 'Change Password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'active-form',
                            'validateOnType' => true,
                            'options' => [
                                'class' => 'form-horizontal',
                            ],
                            'fieldConfig' => [
                                'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                            ],
                                ]
                );
                ?>

                <?= $form->field($model, 'old_pass')->passwordInput(['maxlength' => true])->label('Old Password<span class="required-label"></span>'); ?>

                <?= $form->field($model, 'new_pass')->passwordInput(['maxlength' => true])->label('New Password<span class="required-label"></span>'); ?>

                <?= $form->field($model, 'confirm_pass')->passwordInput(['maxlength' => true])->label('Confirm New Password<span class="required-label"></span>'); ?>

                <div class="box-footer clearfix">
                    <div class="col-sm-offset-3 col-sm-5">
                        <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary pull-right']); ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>