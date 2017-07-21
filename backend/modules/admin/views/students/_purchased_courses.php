<?php

use common\components\Myclass;
use common\models\DlStudent;
use common\models\DlStudentCourse;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudent */
/* @var $form ActiveForm */
?>

<div class="dl-student-form">
    <?php
    echo GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getDlStudentCourses(),
                ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'lesson.lesson_name',
            [
                'header' => 'Schedule Status',
                'attribute' => 'schedule_id',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    return ($scmodel->schedule_id == "0") ? '<span class="label label-danger">Not Assigned</span>' : '<span class="label label-success">Assigned</span>';
                },
            ],
            [
                'attribute' => 'schedule.schedule_date',
                'format' => ['date', 'php:m-d-Y'],
                'options' => ['width' => '10%'],
            ],
            [
                'header' => 'Instructor',
                'attribute' => 'schedule.instructor.first_name',
                'value' => function ($scmodel) {
                    return ($scmodel->schedule_id != "0") ? $scmodel->schedule->instructor->first_name . " " . $scmodel->schedule->instructor->last_name : "-";
                },
            ],
            [
                'attribute' => 'scr_registerdate',
                'format' => ['date', 'php:m-d-Y'],
                'options' => ['width' => '10%'],
            ],
            [
                'label' => 'Preferred Time',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    $preferTime = DlStudentCourse::$preferTime;
                    return ($scmodel->preferred_days != "") ? $preferTime[$scmodel->preferred_days] : "";
                },
            ],
            [
                'label' => 'Preferred days',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    $preferDays = DlStudentCourse::$preferDays;
                    return ($scmodel->preferred_time != "") ? $preferDays[$scmodel->preferred_time] : "";
                },
            ],
            [
                'label' => 'Notes',
                'format' => 'raw',
                'value' => function ($scmodel) {                   
                    return ($scmodel->additional_infos != "") ? $scmodel->additional_infos : "";
                },
            ],
            [
                'attribute' => 'scr_paid_status',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    return ($scmodel->scr_paid_status == "0") ? '<span class="label label-danger">Pending</span>' : '<span class="label label-success">Paid</span>';
                },
            ],
            [
                'attribute' => 'scr_completed_date',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    return ($scmodel->scr_completed_date != "") ? Myclass::dateformat($scmodel->scr_completed_date) : '';
                },
            ],
            //  'scr_certificate_serialno',            
            [
                'attribute' => 'scr_completed_status',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    if ($scmodel->schedule_id>0) {                       
                            if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "1") {
                                $sc_stat = '<span class="label label-success">Completed</span>';
                            } else if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "0") {
                                $url = "javascript:void(0)";
                                $icondisp = '<span title="Click to change the schedule status" class="label label-danger">Not yet complete</span>';                                
                                $sc_stat  = "<div id='stat_flag_" . $scmodel->scr_id . "'>" . Html::a($icondisp, $url, [
                                            'class' => 'scstatus',
                                            'data' => [
                                                'id' => $scmodel->scr_id,
                                            ],
                                        ]) . "</div>";
                            }
                        } else {
                            $sc_stat = '<span class="label label-danger">Not yet complete</span>';
                        }

                        return $sc_stat;                    
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{schedules_assign}',
                'buttons' => [
                    'schedules_assign' => function ($url, $scmodel) {
                        if ($scmodel->schedule_id == 0 && $scmodel->scr_paid_status == 1) {
                            $url = Url::toRoute(['schedules/create', 'scr_id' => $scmodel->scr_id, 'lesson_id' => $scmodel->lesson_id]);
                            $icondisp = '<span title="Assign Student to Schedule" class="fa fa-group"></span>';
                            return Html::a($icondisp, $url);
                        } else if ($scmodel->schedule_id > 0) {
                            $url = Url::toRoute('schedules/scheduledstudents?id=' . $scmodel->schedule_id);
                            $icondisp = '<span title="Scheduled Students" class="fa fa-list"></span>';
                            return Html::a($icondisp, $url);
                        }
                    },
                ],
            ],
        ],
        'emptyText' => '-',
    ]);
    ?>

</div>
<?php
/* For update the status of the schedule */
$callback = Yii::$app->urlManager->createUrl(['admin/schedules/statusupdate']);
$script = <<< JS
      
    jQuery(document).ready(function () { 
         $('.scstatus').on('click', function() {
            var scrid = $(this).data("id");
            var check = confirm("Are you sure want to complete this schedule?");
            if (check == true) {
               $.ajax({
                    url  : "{$callback}",
                    type : "POST",   
                    async: false,
                    data: 'scrid='+scrid,
                    success: function(data) {
                         $("#stat_flag_"+scrid).html("<span class='label label-success'>Completed</span>");   
                    }
               });
            }
            else {
                return false;
            }
            
         });
    });
JS;
$this->registerJs($script, View::POS_END);
?>
