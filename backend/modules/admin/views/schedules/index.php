<?php

use common\models\DlStudentCourse;
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
            [
                'attribute' => 'sch_status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->schedule_id) {
                        $scmodel = DlStudentCourse::find()->where(['schedule_id' => $model->schedule_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "1") {
                                $sc_stat = '<span class="label label-success">Completed</span>';
                            } else if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "0") {
                                $url = "javascript:void(0)";
                                $icondisp = '<span title="Click to change the schedule status" class="label label-danger">Not yet complete</span>';                               
                                $sc_stat = "<div id='stat_flag_" . $scmodel->scr_id . "'>" . Html::a($icondisp, $url, [
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
                    }
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{assign_students}&nbsp;&nbsp;{scheduled_students}&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons' => [
//                    'assign_students' => function ($url, $model) {
//                        $url = Url::toRoute('schedules/assignstudents?id=' . $model->schedule_id);
//                        return Html::a('<span title="Assign Students" class="fa fa-group"></span>', $url);
//                    },
                    'update' => function ($url, $model) {
                        $scmodel = DlStudentCourse::find()->where(['schedule_id' => $model->schedule_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "1") {
                                return "";
                            } else {
                                $url = Url::toRoute('schedules/update?id=' . $model->schedule_id);
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['id' => "updateicon_" . $scmodel->scr_id]);
                            }
                        }
                    },
                    'delete' => function ($url, $model) {
                        $scmodel = DlStudentCourse::find()->where(['schedule_id' => $model->schedule_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "1") {
                                return "";
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-trash" title="Delete"></span>', ['schedules/delete', 'id' => $model->schedule_id], ['id' => "deleteicon_" . $scmodel->scr_id, "data-pjax" => 0, 'onClick' => 'return confirm("Are you sure you want to delete this schedule?") ',  "data-method" => "post"]);
                            }
                        }
                    },
                    'scheduled_students' => function ($url, $model) {
                        $url = Url::toRoute('schedules/scheduledstudents?id=' . $model->schedule_id);
                        return Html::a('<span title="Student Detailed Information" class="glyphicon glyphicon-tasks"></span>', $url);
                    },
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
