<?php

use common\models\SubCategories;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SubCategories */
/* @var $form ActiveForm */
?>

<div class="box-body">
 <div class="col-md-12">
            <div class="pull-right">
                <?= Html::a('Back', ['sub-categories/index'], ['class' => 'btn btn-success']) ?>
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

    <?= $form->field($model, 'category_id')->dropDownList($items, ['prompt' => '--Select Type--'])->label('Category Type<span class="required-label"></span>'); ?>

    <?= $form->field($model, 'subcat_name')->textInput(['maxlength' => true])->label('Sub Category Name<span class="required-label"></span>'); ?>
    <?= $form->field($model, 'subcat_logo')->fileInput() ?>
 <div id="previewimage">
        <img src="" class="image"  height="85" width="100">
    </div>
    
    <div id="oldimage">
         <?php
    if (!$model->isNewRecord) {
        if ($model->subcat_logo != 'no-image.jpg') {?>
            <img src="/backend/web/uploads/subcategory/<?php echo $model->subcat_id . '/' . $model->subcat_logo; ?>" height="75" width="100">
        <?php } else { ?>
            <img src="/backend/web/uploads/no-image.jpg" height="75" width="100">
            <?php } } ?>
            </div>
    <?php
    if ($model->isNewRecord) {
        $model->status = true;
    }
    ?>
    <?= $form->field($model, 'status')->checkbox(['label' => ('Active')])->label('Status') ?>

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
        $('#previewimage').hide();
        $('#subcategories-subcat_logo').on('change', function(e) {
         $('#oldimage').hide();
        var img = URL.createObjectURL(e.target.files[0]);
        $('.image').attr('src', img);
          $('#previewimage').show();
        
            });
         });
JS;
$this->registerJs($script, View::POS_END);
?>