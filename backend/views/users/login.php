<?php

use common\models\Logins;
use common\models\Users;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $login Logins */
/* @var $model Users */
/* @var $form ActiveForm */
$login->isNewRecord ? $this->title = 'Create Login Details' : $this->title = 'Update Login Details';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="box box-primary">
        <div class="box-body">
 <div class="col-md-12">
            <div class="pull-right">
                <?= Html::a('Back', ['users/index'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
            <?php
            $form = ActiveForm::begin([
                        'id' => 'active-form',
                        'options' => [
                            'class' => 'form-horizontal',
                        ],
                        'fieldConfig' => [
                            'template' => "{label}<div class=\"col-sm-5\">{input}<b style='color: #000;'>{hint}</b><div class=\"errorMessage\">{error}</div></div>",
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                        ],
                            ]
            );
            ?>

            <?php // $form->field($login, 'email')->textInput(['readonly' => true])->label('Email<span class="required-label"></span>'); ?>
            <?= $form->field($login, 'username')->textInput(['maxlength' => true])->label('User Name<span class="required-label"></span>'); ?>                         
            <?= $login->isNewRecord ? $form->field($login, 'password_hash')->passwordInput(['maxlength' => true])->label('Password<span class="required-label"></span>') : $form->field($login, 'password_hash')->passwordInput(['value' => ""]) ?>

            <div class="box-footer">
                <div class="col-sm-0 col-sm-offset-2">
                    <?= Html::submitButton($login->isNewRecord ? 'Create' : 'Update', ['class' => $login->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;
                    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
