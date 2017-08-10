<?php

use common\models\DlAdminLessons;
use common\models\DlLocations;
use common\models\DlStudentCourse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

if ($model->schedule_date)
    $model->schedule_date = Yii::$app->formatter->asDate($model->schedule_date, 'php:m/d/Y');

if ($model->start_time == "") {
    $model->start_time = "09:00 am";
    $model->end_time = "11:00 am";
} else {
    $model->start_time = date('h:i a', strtotime($model->start_time));
    $model->end_time = date('h:i a', strtotime($model->end_time));
}

if ($model->isNewRecord)
    $model->schedule_type = 1;

$labelclass = "col-sm-2";
$inputclass = "col-sm-4";
$buttonclass = "col-sm-offset-2";


/* @var $form ActiveForm */
$form = ActiveForm::begin([
            'id' => 'schedule-form',
            'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'validateOnType' => true,
            'fieldConfig' => [
                'template' => "{label}<div class='" . $inputclass . "'>{input}<div class='errorMessage'>{error}</div></div>",
                'labelOptions' => ['class' => $labelclass . ' control-label'],
            ],
        ]);
?>
<div class="box-body">   
    <div id="error_schedule">            
        <div class="alert alert-danger alert-dismissable">              
            The schedule is busy during the times you selected.Please select different time for this lesson.
        </div>
    </div>
     <div id="error_scheduleid">            
        <div class="alert alert-danger alert-dismissable">              
        </div>
    </div>
    
    <?= $form->field($model, 'schedule_date')->textInput(['class' => 'form-control datepicker']) ?>  
    <?= $form->field($model, 'start_time')->textInput(['class' => 'form-control timepicker']) ?>
    <?= $form->field($model, 'end_time')->textInput(['class' => 'form-control timepicker']) ?>

    <?php if (!$model->isNewRecord) { ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">Lesson</label>
            <div class="col-sm-5"><?php echo $model->lesson->lesson_name; ?></div>
            <input id="dbschedules-lesson_id" type="hidden" name="DbSchedules[lesson_id]" value="<?php echo $model->lesson_id; ?>">
        </div>
        <?php
    } else {
        echo $form->field($model, 'lesson_id')->dropDownList(ArrayHelper::map(DlAdminLessons::find()->andWhere('admin_id = :adm_id and status=1', [':adm_id' => Yii::$app->user->identity->adminid])->all(), 'lesson_id', 'lessons.lesson_name'), ['prompt' => '--- Select Lesson ---'])->label("Lesson");
    }
    ?>
    <?php if (!$model->isNewRecord) { ?>
        <div class="form-group field-dbschedules-studentdisp">
            <label class="col-sm-2 control-label">Scheduled Student Info</label>
            <div class="col-sm-5">
                <div id="studinfo">
                    <?php
                   $students_info = ArrayHelper::map(DlStudentCourse::find()->where([
                                        'lesson_id' => $model->lesson_id,
                                        'admin_id' => $model->admin_id,
//                                        'schedule_id' => $model->schedule_id,
                                        'scr_id' => $model->scr_id,
                                        'scr_paid_status' => 1
                                    ])->all(), "scr_id", function($model, $defaultValue) {
                                return $model->studentinfo;
                            });
                    echo isset($students_info[$model->scr_id]) ? $students_info[$model->scr_id] : "";
                    ?>    
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <?= $form->field($model, 'stdcrsid')->dropDownList(array(), array()); ?>
    <?= $form->field($model, 'schedule_type')->radioList([1 => 'Pickup', 2 => 'Office'], ['itemOptions' => ['class' => 'schedule_type'],])->label('Type') ?>
    <?=
    $form->field($model, 'location_id')->dropDownList(ArrayHelper::map(DlLocations::find()->andWhere('admin_id = :adm_id', [':adm_id' => Yii::$app->user->identity->adminid])->all(), 'location_id', function($model, $defaultValue) {
                return $model['address1'] . ',' . $model['city'] . ',' . $model['state'] . ',' . $model['country'] . ' - ' . $model['zip'];
            }
    ))->label("Location");
    ?>
    <?php //$form->field($model, 'status')->radioList([1 => 'Enable', 0 => 'Disbaled'])->label('Status') ?>

</div><!-- /.box-body -->
<?php
if (!$model->isNewRecord) {
    ?>
    <div class="form-group">
        <?php echo Html::hiddenInput('schedule_id', $model->schedule_id, ['class' => 'form-control']); ?>
    </div>
<?php } ?>
<?= $form->field($model, 'instructor_id')->hiddenInput(['value' => $insid = Yii::$app->user->getId()])->label(false); ?>
<div class="box-footer">
    <div class="form-group">
        <div class="col-sm-0 col-sm-offset-2">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['id' => 'schedule-button', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
/* For student dropdown list */
$callback = Yii::$app->urlManager->createUrl(['instructor/schedules/unassignpaidstud']);
/* Check schedule exist of inbetween the schedule date, start and end time */
$chckscheduleexist_callback = Yii::$app->urlManager->createUrl(['instructor/schedules/chckscheduleexist']);
/* Check schedule hours calculated using start and end time */
$schedulehours_callback = Yii::$app->urlManager->createUrl(['instructor/schedules/schedulehoursexist']);
$script = <<< JS
      
    jQuery(document).ready(function () { 
        var schedtype = '{$model->schedule_type}';
        var stdcrsid = '{$model->stdcrsid}';
        $("#error_schedule").hide();
        
         $("#error_scheduleid").hide();
        
        $('#schedule-button').on('click', function() {
           var testing_hours = false;
         $("#error_scheduleid").hide();
        $.ajax({
                url  : "{$schedulehours_callback}",
                type : "POST", 
                dataType: 'json',  
                async: false,
                data: $('.form-group input[type=\'text\'], .form-group input[type=\'hidden\'] , .form-group select'),
                success: function(data) {
                 if(data.leadsCount==1){
                    testing_hours = true;                    
                  }else{                
                    var msg = "Remaining Hours for this Student is "+data.remaining+". Create Schedule before it is Completed."
                    $("#error_scheduleid .alert.alert-danger").text(msg);
                     $("#error_scheduleid").show(); 
                     var target = $(".header");
                      $('html,body').animate({
                            scrollTop: target.offset().top
                          }, 1000);   
                  }  
                
                }
           });
           return testing_hours;    
         }); 
        
        
//        $('#schedule-button').on('click', function() {
//            var testing = false;
//            $("#error_schedule").hide();
//            $.ajax({
//                url  : "{$chckscheduleexist_callback}",
//                type : "POST", 
//                dataType: 'json',  
//                async: false,
//                data: $('.form-group input[type=\'text\'], .form-group input[type=\'hidden\'] , .form-group select'),
//                success: function(data) {
//                 if(data.leadsCount==0){
//                    testing = true;                    
//                  }else{                
//                     $("#error_schedule").show();   
//                  }  
//                }
//           });
//           return testing;    
//        });  
        
        $('#dbschedules-schedule_date').removeClass('hasDatepicker');
                
        if(schedtype!=2)
        $('.field-dbschedules-location_id').hide();
        
        $('.schedule_type').on('ifChecked', function (event) {            
            var stype = $(this).val();
            if(stype==2){
                $('.field-dbschedules-location_id').show();
            }else{
                $('.field-dbschedules-location_id').hide();
            }
        }); 
        
        $('#dbschedules-stdcrsid').selectpicker({
            liveSearch: true,
            maxOptions: 1
        });
                
        var lesson_id  = $('#dbschedules-lesson_id').val();
        
       if(lesson_id!="")
        studentlist(lesson_id);      
        
        $('#dbschedules-lesson_id').on('change', function() {
            var lesson_id     = $(this).val();  
            if(lesson_id!="")    
            studentlist(lesson_id);    
        });
        
        function studentlist(lesson_id){
            $.ajax({
                url  : "{$callback}",
                type : "POST",                   
                data: {
                  id: lesson_id,                       
                },
                success: function(data) {
                  $("#dbschedules-stdcrsid").html(data);
                  if(stdcrsid!=""){         
                    $('#dbschedules-stdcrsid').val(stdcrsid);
                  }
                  $('#dbschedules-stdcrsid').selectpicker('refresh');
                }
           });  
        }
                
    });
JS;
$this->registerJs($script, View::POS_END);
?>
