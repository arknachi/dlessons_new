<?php

use common\models\DlPaymentSearch;
use common\models\DlStudentCourse;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DlPaymentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Instructor Hours';
$this->params['breadcrumbs'][] = $this->title;

$startdate = "";
$enddate = "";

$tot_hours = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $diff =  strtotime($val->end_time) -  strtotime($val->start_time);
        $diff_in_hrs = $diff / 3600;
        $tot_hours += $diff_in_hrs;
    }
}

$totalcount = $dataProvider->getTotalCount();
?>
<div class="dl-payment-index">
    <?php echo $this->render('_search_inshours', ['searchModel' => $searchModel]); ?>   
    <?php if ($searchModel->startdate != "" && $searchModel->enddate != "") { ?>
        <?php if ($totalcount > 0) { ?>
            <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-primary pull-right"> <i class="fa fa-print"></i>  Print</a>    
            <div class="col-lg-12 col-md-12">&nbsp;</div>
        <?php } ?> 
        <div id="Getprintval"> 
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <?=
                    GridView::widget([
                        'layout' => "<div class='panel panel-primary'>"
                        . "<div class='panel-heading'>"
                        . "<div class='pull-right'>{summary}</div>"
                        . "<h3 class='panel-title'>Instructor Hours</h3></div>"
                        . "<div class='panel-body'><h3>Instructor Schedules From {$searchModel->startdate} until {$searchModel->enddate} </h3> <h4>Total hours in this page: <strong>{$tot_hours}</strong></h4>  {items}{pager}</div></div>",
                        'dataProvider' => $dataProvider,
                        //'showFooter' =>true,                      
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'lesson.lesson_name',
                            [
                                'attribute' => 'stdcrsid',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->schedule_id) {
                                        $students_info = ArrayHelper::map(DlStudentCourse::find()->where([
                                                            'lesson_id' => $model->lesson_id,
                                                            'admin_id' => $model->admin_id,
                                                            'schedule_id' => $model->schedule_id,
                                                            'scr_paid_status' => 1
                                                        ])->all(), "schedule_id", function($model, $defaultValue) {
                                                            return $model->student->first_name . " " . $model->student->last_name;
                                                        });
                                        return isset($students_info[$model->schedule_id]) ? $students_info[$model->schedule_id] : "";
                                    }
                                },
                            ],                          
                            'instructor.fullname',
                            [
                                'attribute' => 'schedule_date',
                                'format' => ['date', 'php:m-d-Y'],
                                'options' => ['width' => '10%'],
                            ],
                            [
                                'attribute' => 'start_time',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return date('h:i a', strtotime($model->start_time));
                                },
                            ],
                            [
                                'attribute' => 'end_time',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    return date('h:i a', strtotime($model->end_time));
                                },
                            ],
                            [
                                'attribute' => 'schedule_type',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    if ($model->schedule_type == 1) {
                                        return "Pickup";
                                    } else if ($model->schedule_type == 2) {
                                        return "Office";
                                    }
                                },
                            ],
                            [
                                'header' => 'Total Hours',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $diff =  strtotime($model->end_time) -  strtotime($model->start_time);
                                    $diff_in_hrs = $diff / 3600;
                                    return $diff_in_hrs;
                                }
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>           
    <?php } ?>
</div>
<?php
$js = <<< EOD
$(document).ready(function(){
        
   $("#print_res").click(function() {
        var startdate = $("#dbschedulessearch-startdate").val();
        var enddate = $("#dbschedulessearch-enddate").val();

        $("#startdate_error").hide();    
        $("#enddate_error").hide();

       if(startdate=="")
        {
            $("#startdate_error").show();
            return false;
        }

       if(enddate=="")
        {
            $("#enddate_error").show();
            return false;
        }

        return true;

    });
        
    $("#printdiv").click(function() {   
        var innerContents = document.getElementById("Getprintval").innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=700,height=700,scrollbars=yes,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="/webpanel/themes/admin/css/print.css" /></head><body onload="window.print()">' + innerContents + '</html>');    popupWinindow.document.close();  
    });      
    
});
EOD;
$this->registerJs($js, View::POS_END);
?>
