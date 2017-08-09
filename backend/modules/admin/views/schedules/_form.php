<?php

use common\models\DlAdminLessons;
use common\models\DlInstructors;
use common\models\DlLocations;
use common\models\DlStudentCourse;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

if ($model->schedule_date)
    $model->schedule_date = Yii::$app->formatter->asDate($model->schedule_date, 'php:m/d/Y');

if ($model->start_time == "") {
    $model->start_time = "09:00 am";
    $model->end_time = "11:00 am";
} else {
    $model->start_time = date('h:i a', strtotime($model->start_time));
    $model->end_time = date('h:i a', strtotime($model->end_time));
}

if ($model->isNewRecord) {
    $model->schedule_type = 1;

    $labelclass = "col-sm-4";
    $inputclass = "col-sm-7";
    $buttonclass = "col-sm-offset-5";
} else {
    $labelclass = "col-sm-2";
    $inputclass = "col-sm-3";
    $buttonclass = "col-sm-offset-2";
}

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
            Schedule hour is lesser than the times you selected.Please select different time for this lesson.
        </div>
    </div>


    <?php if (!$model->isNewRecord) { ?>
        <?= $form->field($model, 'schedule_date')->textInput(['class' => 'form-control datepicker']) ?>   
        <?= $form->field($model, 'instructor_id')->dropDownList($instructors, ['prompt' => '--- Select Instructor ---'])->label("Instructor"); ?>
        <?= $form->field($model, 'start_time')->textInput(['class' => 'form-control timepicker']) ?>
        <?= $form->field($model, 'end_time')->textInput(['class' => 'form-control timepicker']) ?>

        <div class="form-group">
            <label class="col-sm-2 control-label">Lesson</label>
            <div class="col-sm-5"><?php echo $model->lesson->lesson_name; ?></div>
            <input id="dbschedules-lesson_id" type="hidden" name="DbSchedules[lesson_id]" value="<?php echo $model->lesson_id; ?>">
        </div>
        <?php
    } else {
        echo $form->field($model, 'lesson_id')->dropDownList(ArrayHelper::map(DlAdminLessons::find()->andWhere('admin_id = :adm_id and status=1', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'lesson_id', 'lessons.lesson_name'), ['prompt' => '--- Select Lesson ---'])->label("Lesson");
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
    <?php
    if (!$model->isNewRecord) {
        echo $form->field($model, 'schedule_type')->dropDownList([1 => 'Pickup', 2 => 'Office'], ['class' => 'form-control schedule_type'])->label('Type')
        ?>

        <div class="location_id" style="display: none;">
            <?php
            echo $form->field($model, 'location_id')->dropDownList(ArrayHelper::map(DlLocations::find()->andWhere('admin_id = :adm_id', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'location_id', function($model, $defaultValue) {
                        return $model['address1'] . ',' . $model['city'] . ',' . $model['state'] . ',' . $model['country'] . ' - ' . $model['zip'];
                    }
            ))->label("Location");
            ?>
        </div> 
    <?php } ?>
    

    <?php if ($model->isNewRecord) { ?>
        <?php
        DynamicFormWidget::begin([
            'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
            'widgetBody' => '.container-items', // required: css class selector
            'widgetItem' => '.item', // required: css class
//        'limit' => 4, // the maximum times, an element can be cloned (default 999)
            'min' => 1, // 0 or 1 (default 1)
            'insertButton' => '.add-item', // css class
            'deleteButton' => '.remove-item', // css class
            'model' => $modelsschedules[0],
            'formId' => 'schedule-form',
            'formFields' => [
                'schedule_date',
                'instructor_id',
                'start_time',
                'end_time',
                'location_id',
            ],
        ]);
        ?>



        <div class="container-items"><!-- widgetContainer -->

            <?php foreach ($modelsschedules as $index => $modelschedules): ?>
                <div class="item panel panel-default"><!-- widgetBody -->
                    <div class="panel-heading">
                        <span class="panel-title-schedule">Schedule: <?= ($index + 1) ?></span>
                        <button type="button" class="pull-right remove-item btn btn-danger btn-xs"><i class="fa fa-minus"></i></button>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body schedule">                        
                        <?= $form->field($modelschedules, "[{$index}]schedule_date")->textInput(['class' => 'form-control schedule_date datepicker', 'data-index' => $index]) ?>                           
                        <?= $form->field($modelschedules, "[{$index}]instructor_id")->dropDownList($instructors, ['prompt' => '--- Select Instructor ---', 'class' => 'form-control instructor_id', 'data-index' => $index])->label("Instructor"); ?>
                        <?= $form->field($modelschedules, "[{$index}]start_time")->textInput(['class' => 'form-control timepicker']) ?>
                        <?= $form->field($modelschedules, "[{$index}]end_time")->textInput(['class' => 'form-control timepicker']) ?>
                        <?= $form->field($modelschedules, "[{$index}]schedule_type")->dropDownList([1 => 'Pickup', 2 => 'Office'], ['class' => 'form-control schedule_type'])->label('Type') ?>                       
                        <div class="location_id" style="display: none;">        
                            <?=
                            $form->field($modelschedules, "[{$index}]location_id")->dropDownList(ArrayHelper::map(DlLocations::find()->andWhere('admin_id = :adm_id', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'location_id', function($modelschedules, $defaultValue) {
                                        return $modelschedules['address1'] . ',' . $modelschedules['city'] . ',' . $modelschedules['state'] . ',' . $modelschedules['country'] . ' - ' . $modelschedules['zip'];
                                    }
                                    ), ['class' => 'form-control '])->label("Location");
                            ?> </div></div>
                </div>

            <?php endforeach; ?>

        </div>
        <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Split Schedule</button>
        <div class="clearfix"></div>
        <?php DynamicFormWidget::end(); ?>
    <?php } ?>

    <?php //$form->field($model, 'status')->radioList([1 => 'Enable', 0 => 'Disbaled'])->label('Status')   ?>

</div><!-- /.box-body -->
<?php
if (!$model->isNewRecord) {
    ?>
    <?= $form->field($model, 'scr_completed_status')->dropDownList([0 => 'Not Yet Complete', 1 => 'Completed', 2 => 'Cancelled'], ['class' => 'form-control'])->label('Status') ?>
    <div class="form-group">
        <?php echo Html::hiddenInput('schedule_id', $model->schedule_id, ['class' => 'form-control']); ?>
        <?php echo Html::hiddenInput('scr_id', $model->scr_id, ['class' => 'form-control']); ?>
    </div>
<?php } ?>
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
$callback = Yii::$app->urlManager->createUrl(['admin/schedules/unassignpaidstud']);
/* On instructor selection to set the instructor  available_date , time */
$ins_si_callback = Yii::$app->urlManager->createUrl(['admin/schedules/ins_schedule_info']);
/* Check the available instructor on selected date */
$avai_ins_callback = Yii::$app->urlManager->createUrl(['admin/schedules/available_ins_info']);
/* Check schedule exist of inbetween the schedule date, start and end time */
$chckscheduleexist_callback = Yii::$app->urlManager->createUrl(['admin/schedules/chckscheduleexist']);
/* Check schedule hours calculated using start and end time */
$schedulehours_callback = Yii::$app->urlManager->createUrl(['admin/schedules/schedulehoursexist']);
$script = <<< JS
      
    $(document).ready(function () { 
        
        $(".dynamicform_wrapper .schedule_date").each(function(index) {               
                $(this).removeClass('hasDatepicker');        
            });
        $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
               
            $(".dynamicform_wrapper .panel-title-schedule").each(function(index) {
                $(this).html("Schedule: " + (index + 1))            
            });   
            
            $(".dynamicform_wrapper .schedule_date").each(function(index) {               
                $(this).attr("data-index",(index))                    
            });
         $(".dynamicform_wrapper .instructor_id").each(function(index) {               
                $(this).attr("data-index",(index))                    
            });
            $('.timepicker').timepicker({
                                    timeFormat: 'hh:mm tt'
                                }); 
            initdatepicker();
        });

        $(".dynamicform_wrapper").on("afterDelete", function(e) {
            $(".dynamicform_wrapper .panel-title-schedule").each(function(index) {
                $(this).html("Schedule: " + (index + 1))
            });
        $(".dynamicform_wrapper .schedule_date").each(function(index) {                
                $(this).attr("data-index",(index))                    
            });
        });
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
                 if(data.leadsCount==1 || data.leadsCount==2 ){
                    testing_hours = true;                    
                  }else{                
                     $("#error_scheduleid").show();   
                  }  
                
                }
           });
           return testing_hours;    
         }); 
        
                
//           $('#schedule-button').on('click', function() {
//         var testing = false;
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
           
                
        initdatepicker(); 
        $("body").on('change',".schedule_type",function(){
            if($(this).val() == 2){
                $(this).closest('.panel-body').find('.location_id').show();
            }else{
                $(this).closest('.panel-body').find('.location_id').hide();
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
                
        $(document).on('change','.instructor_id', function() {
            var ins_id = $(this).val();     
            if(ins_id!="")  
              instructorlist(ins_id);   
        });     
            
        function instructorlist(ins_id){    
            $.ajax({
                url  : "{$ins_si_callback}",
                type : "POST", 
                dataType: 'json',    
                data: $('.schedule .form-group input[type=\'text\'] , .schedule .form-group select'),
                success: function(data) {
                 if(data.start_time){
                    $(data.start_time_id).val(data.start_time);
                    $(data.end_time_id).val(data.end_time);
                  }  
                }
           });  
        }        
    });
    function initdatepicker(){
                $('.schedule_date').datepicker({
            onSelect: function(dateText, inst) {   
                
                var index = $(this).data('index');      
                $.ajax({
                url  : "{$avai_ins_callback}",
                type : "POST",                   
                data: {
                  selecteddate: dateText,                       
                },
                success: function(data) {
                  $("#dbschedules-"+index+"-instructor_id").html(data);                  
                  $('#dbschedules-'+index+'-instructor_id').selectpicker('refresh');
                }
             });  
            }
        }); 
        }
JS;
$this->registerJs($script, View::POS_END);
?>
