<?php

use yii2fullcalendar\yii2fullcalendar;
use yii\helpers\Html;
use yii\web\JsExpression;

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;

//$profesional_count = ProfessionalDirectory::model()->count();
//$retailer_count = RetailerDirectory::model()->count();
//$supplier_count = SuppliersDirectory::model()->count();
//$rep_count = RepCredentials::model()->count("rep_role='single'");
$view = "View All <i class='fa fa-arrow-circle-right'></i>";
?>
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">   
                <h3><?php echo $schedule_count; ?></h3>
                <p>Schedules</p>
            </div>
            <div class="icon"><i class="ion-clock"></i></div>
            <?php echo Html::a($view, ['/instructor/schedules'], $options = ["class" => 'small-box-footer']); ?>
        </div>
    </div>    
</div>
<div class="row">
    <div class="col-lg-12 col-xs-6">
        <?=
        yii2fullcalendar::widget(array(
            'clientOptions' => [
                'header' => [
                    'right' => 'month,agendaWeek,agendaDay'
                ],
               'displayEventTime'=> false,
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
                            $('#eventContent').dialog({ modal: true, title: 'Schedule Informations', width:350});
                        });

                } "),
            ],
            'events' => $events,
        ));
        ?>
    </div>
</div>
<div id="eventContent" title="Event Details" style="display:none;">
        Start Time: <span id="startTime"></span><br>
        End Time: <span id="endTime"></span><br><br>
        <p id="eventInfo"></p>
        <p><strong><a id="eventLink" class="btn btn-primary" href="">See More Info</a></strong></p>
    </div>