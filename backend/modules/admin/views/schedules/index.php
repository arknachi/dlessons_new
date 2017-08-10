<?php

use common\models\DbSchedules;
use common\models\DlLessons;
use common\models\DlStudentCourse;
use common\models\DlStudent;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Schedules';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="db-schedules-index">
    <p style="text-align: right;">
        <?= Html::a('Create Schedule', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
//     [
//                'attribute' => 'schedule_date',
//                'format' => ['date', 'php:d-m-Y'],
//                'options' => ['width' => '10%'],
//                'filter' => false,
//                'enableSorting' => false,
//            ],    

    GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'lesson.lesson_name',
             [
                'attribute' => 'Student Name',
                'format' => 'raw',
                'value' => function ($model) {     
        if($model->dlStudentCourses){
                        return $model->dlStudentCourses->student->first_name.' '.$model->dlStudentCourses->student->last_name;
        }
                },
            ],
            [
                'header' => 'Total Hours',
                'attribute' => 'lesson.hours',
            ],
            [
                'header' => 'Remaining Hours',
                'format' => 'raw',
                'value' => function ($model) {
                    $studcrid = $model->scr_id;
                    $remainings = DbSchedules::find()->where('scr_id = :tour_id and scr_completed_status != :id and isDeleted = :delval', ['tour_id' => $studcrid, 'id' => 2, 'delval'=>'0'])->all();
                    $sum = 0;
                    foreach ($remainings as $remaining) {
                        $sum += $remaining->hours;
                    }
                    $les_info = DlLessons::find()->Where([
                                'lesson_id' => $model->lesson_id,])->one();
                    $totalh = $les_info->hours;
                    $different = abs($les_info->hours - $sum);
                    return $different;
                },
            ],
           
            [
                'attribute' => 'Overall Status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->schedule_id) {
//                        $schedule_count = DbSchedules::find()->where('scr_id = :tour_id and scr_completed_status = :id', ['tour_id' => $model->scr_id, 'id' => 0])->count();
//                        $scmodel = DlStudentCourse::find()->where(['scr_id' => $model->scr_id])->one();
//                        if ($schedule_count) {
                         if($model->dlStudentCourses){
                        if($model->dlStudentCourses->scr_completed_status =='0'){
                            $sc_stat = '<span title="Click to change the schedule status" class="label label-danger">Not yet complete</span>';
                        }else{
                            $sc_stat = '<span class="label label-success">Completed</span>';
                        }
                        return $sc_stat;
                         }
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{assign_students}&nbsp;&nbsp;{scheduled_students}&nbsp;&nbsp;{view}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons' => [
//                    'assign_students' => function ($url, $model) {
//                        $url = Url::toRoute('schedules/assignstudents?id=' . $model->schedule_id);
//                        return Html::a('<span title="Assign Students" class="fa fa-group"></span>', $url);
//                    },
                    'view' => function ($url, $model) {
                        $url = Url::toRoute('schedules/view?id='.$model->scr_id);
                        return Html::a('<span title="Student Detailed Information" class="glyphicon glyphicon-tasks"></span>', $url);
                    },
                  'delete' => function($url, $model) {
                         if($model->dlStudentCourses){
                        if ($model->dlStudentCourses->scr_paid_status == "1" && $model->dlStudentCourses->scr_completed_status == "0") {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['schedules/deleteall', 'id' => $model->scr_id], [
                                        'class' => '',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to delete this schedule?',
                                            'method' => 'post',
                                        ],
                            ]);
                        }  
                           }
                  },
                            
                            
//                    'scheduled_students' => function ($url, $model) {
//                        $url = Url::toRoute('schedules/scheduledstudents?id=' . $model->schedule_id);
//                        return Html::a('<span title="Student Detailed Information" class="glyphicon glyphicon-tasks"></span>', $url);
//                    },
                ],
            ],
        ],
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
                         $("#updateicon_"+scrid).remove();
                         $("#deleteicon_"+scrid).remove();
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