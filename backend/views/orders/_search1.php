<?php

use common\models\DlStudentSearch;
use common\models\OrderStatus;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudentSearch */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
//            'fieldConfig' => [
//                    'template' => "{label}<div class=\"col-sm-4\">{input}</div>",
//                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
//                ],
        ]);
$status_name=ArrayHelper::map(OrderStatus::find()->where('status=:id', ['id' => 1])->all(), 'status_position_id', 'status_name');

?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="panel panel-info advance_search">
            
            <!--<section class="content">-->
                <div class="row">
                   
                    
                    <div class="col-lg-3 col-md-3 ">
                        <div class="form-group">
                            <?php echo $form->field($model, 'started_at')->textInput(['class' => 'form-control datepicker'])->label('Start Date'); ?>                  
                        </div>
                    </div> 
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">

                            <?php echo $form->field($model, 'ended_at')->textInput(['class' => 'form-control datepicker'])->label('End Date'); ?>                  
                        </div>
                    </div> 

                   
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php
                            //echo $form->field($model, 'student.studentProfile.dob');
                            echo $form->field($model, 'status')->dropdownList( $status_name, ['class' => 'form-control', 'id' => null, 'prompt' => 'All']);
                            ?>                  
                        </div>
                    </div>  

                    <div class="col-lg-3 col-md-3 reset1 ">
                        <div class="form-group">
                            <label>&nbsp;</label>                        
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?> &nbsp;
                             <!--<button type="reset">Reset</button>-->
                      <?php // Html::resetButton('Reset', ['class' => 'btn btn-primary ']) ?>
                        </div>
                    </div>

                </div>
            <!--</section>-->
        </div>
    </div>
</div>

<?php
ActiveForm::end();
?>
<?php
/* For Subcategory dropdown list */

$script = <<< JS
           jQuery(document).ready(function () { 
              
        
        var today = new Date();
        $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            autoclose:true,
            endDate: "today",
            maxDate: today
        })

        
        
//            $('.datepicker').datepicker({
//    format: 'mm/dd/yyyy',
//});

         });
JS;
$this->registerJs($script, View::POS_END);
?>