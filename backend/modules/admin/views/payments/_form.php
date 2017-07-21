<?php

use common\models\DlPayment;
use common\models\DlStudentCourse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlPayment */
/* @var $form ActiveForm */

$students_list = ArrayHelper::map(DlStudentCourse::find()->where([
                    'admin_id' => Yii::$app->user->identity->ParentAdminId,
                    'scr_paid_status' => 0
                ])->all(), "scr_id", function($model, $defaultValue) {
            return $model->student->username . "(" . $model->lesson->lesson_name . ")";
        });
?>
<div class="dl-payment-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'payment-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    <div class="box-body">   
        <?php if (!$model->isNewRecord) {
                if($model->payment_type=="CC")
                    $model->payment_date = date("Y-m-d",strtotime($model->created_at));
            
                $scmodel = DlStudentCourse::findOne($model->scr_id);
                $student_course_info = $scmodel->student->username . " ( " . $scmodel->lesson->lesson_name . " )";
            ?>
            <div class="form-group">
                <label class="col-sm-2 control-label">Student Course</label>
                <div class="col-sm-5"><?php echo $student_course_info; ?></div>       
            </div>
            <?php
        } else {
            echo $form->field($model, 'scr_id')->dropDownList($students_list, ['class' => 'form-control','prompt' => '--- Select Student Course ---'])->label("Student Course");
        }
        ?>
        <?= $form->field($model, 'payment_date')->textInput(['class' => 'form-control datepicker']) ?>
        <?= $form->field($model, 'payment_type')->dropDownList(DlPayment::$payment_types, ['class' => 'form-control']); ?>
        <?= $form->field($model, 'cheque_no')->textInput(['class' => 'form-control']) ?>       
        <?= $form->field($model, 'payment_amount')->textInput(['class' => 'form-control']) ?>        
        <?php // $form->field($model, 'payment_trans_id')->textInput(['maxlength' => true, "disabled" => "disabled"]) ?>
       
        <?= $form->field($model, 'payment_status')->dropDownList([ '1' => 'paid','0' => 'pending']); ?>
        <?= $form->field($model, 'payment_notes')->textarea(['rows' => 6]) ?>
    </div>
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$script = <<< JS
      
    jQuery(document).ready(function () { 
                
        var ptype = '{$model->payment_type}';
        
        if(ptype!=""){
            if(ptype=="cheque")
            $('.field-dlpayment-cheque_no').show();     
            else
            $('.field-dlpayment-cheque_no').hide();
        }
        
        $('#dlpayment-payment_type').on('change', function() {
                var ptype = $(this).val();
                if(ptype=="cheque")
                 $('.field-dlpayment-cheque_no').show();
                else
                 $('.field-dlpayment-cheque_no').hide();
                
        });  
    });
JS;
$this->registerJs($script, View::POS_END);
?>
