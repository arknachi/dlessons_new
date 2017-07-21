<?php

use yii2fullcalendar\yii2fullcalendar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
$view = "View All <i class='fa fa-arrow-circle-right'></i>";
?>
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6" id="Lessons" style="display:none;">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?php echo $lessons_count; ?></h3>
                <p> Lessons </p>
            </div>
            <div class="icon"> <i class="ion-ios-paper"></i> </div>
            <?php echo Html::a($view, ['/admin/lessons'], $options = ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->

    <div class="col-lg-3 col-xs-6" id="Instructors" style="display:none;">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?php echo $ins_count; ?></h3>
                <p>Instructors </p>
            </div>
            <div class="icon"> <i class="ion-android-people"></i></div>
            <?php echo Html::a($view, ['/admin/instructors'], $options = ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->

    <div class="col-lg-3 col-xs-6" id="Schedules" style="display:none;">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">   
                <h3><?php echo $schedule_count; ?></h3>
                <p>Schedules</p>
            </div>
            <div class="icon"><i class="ion-clock"></i></div>
            <?php echo Html::a($view, ['/admin/schedules'], $options = ["class" => 'small-box-footer']); ?>
        </div>
    </div>

    <div class="col-lg-3 col-xs-6" id="Students" style="display:none;">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?php echo $student_count; ?></h3>
                <p>Students </p>
            </div>
            <div class="icon">  <i class="ion-ios-people"></i>  </div>
            <?php echo Html::a($view, ['/admin/students'], $options = ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->



    <div class="col-lg-3 col-xs-6" id="Payments" style="display:none;">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">   
                <h3>&nbsp;</h3>
                <p>Payments</p>
            </div>
            <div class="icon"><i class="ion-social-usd"></i></div>
            <?php echo Html::a($view, ['/admin/payments'], $options = ["class" => 'small-box-footer']); ?>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-12 col-xs-6">
        <?php
        $create_schedule = Url::toRoute(['schedules/create']);
        echo yii2fullcalendar::widget(array(
            'clientOptions' => [
                'header' => [
                    'right' => 'month,agendaWeek,agendaDay'
                ],
                'displayEventTime' => false,
                'dayClick' => new JsExpression("
                    function(date, jsEvent, view) {                   
                        var clickeddate = date.format();  
                        var create_schedule_url = '{$create_schedule}?schedule_date='+clickeddate;
                        $(location).attr('href', create_schedule_url)
                    } "),
                'eventRender' => new JsExpression("
                    function(event, element, view) {
                        var title = element.find( '.fc-title' );
                        title.html( title.text() );
                        
                        element.attr('href', 'javascript:void(0);');
                        element.click(function() {
                            $('#startTime').html(moment(event.start).format('M/D/Y h:mm A'));
                            $('#endTime').html(moment(event.end).format('M/D/Y h:mm A'));
                            $('#eventInfo').html(event.description);
                            $('#eventLink').attr('href', event.url);
                            
                            if(event.color=='#00A65A')
                            {
                                $('#eventLink').html('Create Schedule');
                                $(location).attr('href', event.url);
                            }else{
                                $('#eventLink').html('See More Info');
                                $('#eventContent').dialog({ modal: true, title: 'Schedule Informations', width:350});
                            }    
                            
                           
                        });

                    } "),
            ],
            'events' => $events,
        ));
        ?>
    </div>
    <div id="eventContent" title="Event Details" style="display:none;">
        Start Time: <span id="startTime"></span><br>
        End Time: <span id="endTime"></span><br><br>
        <p id="eventInfo"></p>
        <p><strong><a id="eventLink" class="btn btn-primary" href="">See More Info</a></strong></p>
    </div>
    <?php
    $dash_obj = (isset($setting_model)) ? $setting_model->option_value : "";
    if ($dash_obj != "") {
        $script = <<< JS
      
jQuery(document).ready(function ($) { 
    var dashboard_dispvals = {$dash_obj};
    $.each(dashboard_dispvals, function( index, value ) {
        $("#"+value).show();
    });
});
JS;
        $this->registerJs($script, View::POS_END);
    }
    ?>
