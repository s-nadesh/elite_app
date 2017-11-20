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
                        <?php echo $form->field($model, 'started_at')->textInput(['class' => 'form-control datepicker','id'=>'txtFrom'])->label('Start Date'); ?>                  
                    </div>
                </div> 
                <div class="col-lg-3 col-md-3">
                    <div class="form-group">
                        <?php echo $form->field($model, 'ended_at')->textInput(['class' => 'form-control datepicker','id'=>'txtTo'])->label('End Date'); ?>                  
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
        $("#txtFrom").datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: '0',
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#txtTo").datepicker("option", "minDate", dt);
        }
    });
    $("#txtTo").datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: '0',
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#txtFrom").datepicker("option", "maxDate", dt);
        }
    });
    });
JS;
$this->registerJs($script, View::POS_END);
?>