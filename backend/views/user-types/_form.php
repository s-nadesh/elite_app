<?php

use common\models\Rights;
use common\models\UserTypes;
use common\models\UserTypesRights;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model UserTypes */
/* @var $form ActiveForm */

?>
<div class="box-body">
     <div class="col-md-12">
            <div class="pull-right">
                <?= Html::a('Back', ['user-types/index'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'active-form',
                'options' => [
                    'class' => 'form-horizontal',
                ],
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
                    ]
    );
    ?>
    <?= $form->field($model, 'type_name')->textInput(['maxlength' => true])->label('Type Name<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'type_code')->textInput(['maxlength' => true])->label('Type Code<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'visible_site')->checkbox(['label' => ('Active ')])->label('List in App <br/> (Email optional)') ?>
    
    <?= $form->field($model, 'email_app_login')->checkbox(['label' => ('Active ')])->label('App Login <br/> (Email compulsory)') ?>

    <?= $form->field($model, 'reorder_notify')->checkbox(['label' => ('Active ')])->label('Reorder Notification') ?>
    
    <?= $form->field($model, 'update_rate')->checkbox(['label' => ('Active ')])->label('Update Rate') ?>
    <?php
    if ($model->isNewRecord) {
        $model->status = true;
    }
    ?>
    <?= $form->field($model, 'status')->checkbox(['label' => ('Active ')])->label('Status') ?>
    <?php
    
    if (!$model->isNewRecord && !empty($get)) {
        $model->rightslist = $get;
    }
    ?>
    <?=
    $form->field($model, 'rightslist')->checkBoxList(Rights::getRightList(), [
        'multiple' => true,
        'size' => 4,
    ])
    ?> 
    <div class="box-footer">
        <div class="col-sm-0 col-sm-offset-2">
<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>&nbsp;&nbsp;
    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS
           jQuery(document).ready(function () { 
        
      $('#usertypes-rightslist').find('input[value="1"]').click(function() {
            $('input[value=3]').attr('checked', true);
   });
         });
JS;
$this->registerJs($script, View::POS_END);
?>