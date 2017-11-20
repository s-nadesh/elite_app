<?php

use common\models\OrderStatus;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model OrderStatus */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);

$status_name = OrderStatus::prepareOrderStatus();
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="panel panel-info advance_search">
            <div class="row">
                <div class="col-lg-3 col-md-3 ">
                    <div class="form-group">
                        <?php echo $form->field($model, 'started_at')->textInput(['class' => 'form-control datepicker', 'id' => 'from_date'])->label('Start Date'); ?>                  
                    </div>
                </div> 
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <?php echo $form->field($model, 'ended_at')->textInput(['class' => 'form-control datepicker', 'id' => 'to_date'])->label('End Date'); ?>                  
                    </div>
                </div> 
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <?php
                        echo $form->field($model, 'order_status_id')->dropdownList($status_name, ['class' => 'form-control', 'prompt' => 'All']);
                        ?>                  
                    </div>
                </div>  
                <div class="col-lg-3 col-md-3 reset1 ">
                    <div class="form-group">
                        <label>&nbsp;</label>                        
                        <?= Html::submitButton('Search', ['class' => 'btn btn-primary ']) ?>
                        <?= Html::a('Reset', ['index'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
ActiveForm::end();
?>
<?php
$script = <<< JS
    jQuery(document).ready(function () { 
        $("#from_date").datepicker({          
          format: 'yyyy-mm-dd',
          autoclose: true,
          endDate: new Date(),
        }).on('changeDate', function (selected) {
            var startDate = new Date(selected.date.valueOf());
            $('#to_date').datepicker('setStartDate', startDate);
        });

        $("#to_date").datepicker({
           format: 'yyyy-mm-dd',
           autoclose: true,
           endDate: new Date(),
        }).on('changeDate', function (selected) {
           var endDate = new Date(selected.date.valueOf());
           $('#from_date').datepicker('setEndDate', endDate);       
        });
    });
JS;
$this->registerJs($script, View::POS_END);
?>