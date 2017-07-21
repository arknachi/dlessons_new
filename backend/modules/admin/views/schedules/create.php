<?php

use common\models\DbSchedules;
use yii2fullcalendar\yii2fullcalendar;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model DbSchedules */

$this->title = 'Create Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-5 col-sm-5 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php echo $this->render('_form', ['model' => $model, 'instructors' => $instructors,'modelsschedules'=>$modelsschedules]); ?>
             </div>
        </div>
    </div>
    <div class="col-md-7 col-sm-7 col-xs-12">   
        <?php 
        echo yii2fullcalendar::widget(array(
            'id' => 'calendar',
            'clientOptions' => [
                'header' => [
                    'right' => ''
                ],
                'displayEventTime'=> false,                
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