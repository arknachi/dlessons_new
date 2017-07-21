<?php

use common\models\DlInstructors;
use yii2fullcalendar\yii2fullcalendar;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model DlInstructors */

$this->title = 'Update Instructor';
$this->params['breadcrumbs'][] = ['label' => 'Instructors', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-5 col-sm-5 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php echo  $this->render('_form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-7 col-sm-7 col-xs-12">       
        <h3>Available Times</h3>    
        <?php 
        $create_schedule = Url::toRoute(['instructors/availabletimes']);
        echo yii2fullcalendar::widget(array(
            'id' => 'calendar',
            'clientOptions' => [
                'header' => [
                    'right' => ''
                ],
                'displayEventTime'=> false,
                'dayClick' => new JsExpression("
                    function(date, jsEvent, view) {                   
                        var clickeddate = date.format();  
                        var create_schedule_url = '{$create_schedule}';
                        var ins_id = '{$model->instructor_id}';
                         $.ajax({
                            url  : create_schedule_url,
                            type : 'POST',                   
                            data: {
                              ins_id: ins_id, 
                              schedule_date: clickeddate
                            },
                            success: function(data) {
                                $('#current-weeks').html(data);                               
                                $('.timepicker').timepicker({
                                    timeFormat: 'hh:mm tt'
                                }); 
                                
                                $(\"input[type='checkbox']:not(.simple)\").iCheck({
                                    checkboxClass: 'icheckbox_minimal',
                                    radioClass: 'iradio_minimal'
                                });
                            }
                       }); 
                       
                        $('#eventContent').dialog({ modal: true, title: 'Set Available Time', width:600});
                    } "),
                'eventRender' => new JsExpression("
                    function(event, element, view) {
                        var title = element.find( '.fc-title' );
                        title.html( title.text() );
                    } "),
            ],
            'events' => $events,
        ));
        ?>
    </div>
</div>
<div id="eventContent" title="Event Details" style="display:none;">
    <div id="current-weeks"></div> 
</div>
<?php
$set_times_ins = Url::toRoute(['instructors/settimesins']);
$script = <<< JS
    jQuery(document).ready(function () {  
        
        $(document).on('ifChecked', '#start_checkall', function() {
            var startval = $("input[name='start_time[]']").map(function(i){
                                if(i==0){
                                    return $(this).val();     
                                }
                            }).get();
           
            $(".starttime").each(function(){
                 $(this).val(startval);
            })                
       
        });
        
        $(document).on('ifChecked', '#end_checkall', function() {
            var endval = $("input[name='end_time[]']").map(function(i){
                                if(i==0){
                                    return $(this).val();     
                                }
                            }).get();
           
            $(".endtime").each(function(){
                 $(this).val(endval);
            })                
       
        });
        
        $(document).on('click', '.availabletimes', function() {      
            
           $.ajax({
                type:"POST",
                dataType: "json",
                url:'{$set_times_ins}',
                data:$("#available-form input").serialize(), 
                success: function(response){
                    $("#eventContent").dialog("close");
             
                    $('#calendar').fullCalendar('removeEvents');
                    $('#calendar').fullCalendar('addEventSource', response);
                    $('#calendar').fullCalendar('rerenderEvents');
                }
            });            
          return false;
        });
    });
JS;
$this->registerJs($script, View::POS_END);
?>