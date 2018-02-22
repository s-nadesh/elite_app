<?php

use common\models\Users;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Users */
/* @var $form ActiveForm */
?>
<div class="box-body">
 <div class="col-md-12">
            <div class="pull-right">
                <?= Html::a('Back', ['users/index'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'enableClientValidation' => true,
                'validateOnSubmit' => true,
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
   
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Name<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'user_type_id')->dropDownList($items, ['prompt' => '--Select Type--'])->label('Type<span class="required-label"></span>'); ?>
 
        <?php echo $form->field($model, 'show_in_app')->hiddenInput(['value' => ''])->label(false); ?>
    
    <?= $form->field($model, 'email')->textInput()->label('Email<span class="required-label label1"></span>'); ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
    <?php
    if ($model->isNewRecord) {
        $model->status = true;
    }
    ?>
    <?= $form->field($model, 'status')->checkbox(['label' => ('Active ')])->label('Status') ?>

    <div class="box-footer">
        <div class="col-sm-0 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> &nbsp;&nbsp;
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$typecallback = Yii::$app->urlManager->createUrl(['/users/gettype_showinapp']);
$script = <<< JS
           jQuery(document).ready(function () { 
         $('.label1').hide();
        $('#users-user_type_id').on('change', function(e) {
         var type = $(this).val();
        $.ajax({
                url  : "{$typecallback}",
                type : "POST",                   
                data: {
                  id: type,                       
                },
                success: function(data1) {
               if(data1==1){
                  $("#users-show_in_app").val(data1);
                $('.label1').show();
                }else{
                 $("#users-show_in_app").val(0);
                 $('.label1').hide();
                }
                }
           });  
        
            });
         });
JS;
$this->registerJs($script, View::POS_END);
?>