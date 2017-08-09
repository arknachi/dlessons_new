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
    <?php
   if(Yii::$app->user->identity->accessschedule)
   {
    ?>
    <p style="text-align: right;">
        <?= Html::a('Create Schedule', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
   <?php }?>
    <?php
    echo GridView::widget([
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
            'schedule_date:date',
            'start_time:time',
            'end_time:time',
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
                'attribute' => 'sch_status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->schedule_id) {
                        $scmodel = DlStudentCourse::find()->where(['scr_id' => $model->scr_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" && $model->scr_completed_status == "1") {
                                $sc_stat = '<span class="label label-success">Completed</span>';
                            } else if ($scmodel->scr_paid_status == "1" && $model->scr_completed_status == "0") {
                                $url = "javascript:void(0)";
                                $icondisp = '<span title="Click to change the schedule status" class="label label-danger">Not yet complete</span>';
                                $sc_stat = "<div id='stat_flag_" . $scmodel->scr_id . "'>" . Html::a($icondisp, $url, [
                                            'class' => 'scstatus',
                                            'data' => [
                                                'id' => $scmodel->scr_id,
                                            ],
                                        ]) . "</div>";
                            } else if ($scmodel->scr_paid_status == "1" && $model->scr_completed_status == "2") {
                                $sc_stat = '<span class="label label-info">Cancelled</span>';
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
                'template' => '{scheduled_students}&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons' => [
                    'scheduled_students' => function ($url, $model) {
                    if ($model->schedule_id) {
                    $url = Url::toRoute('schedules/scheduledstudents?id=' . $model->schedule_id);
                    return Html::a('<span title="Student Detailed Information" class="glyphicon glyphicon-tasks"></span>', $url);
                    }
                    
                    },
                    'update' => function ($url, $model) {
                        $scmodel = DlStudentCourse::find()->where(['scr_id' => $model->scr_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "1") {
                                return "";
                            } else {
                                $insid = Yii::$app->user->getId();
                                if ($model->role == 2 && $model->created_by == $insid) {
                                    $url = Url::toRoute('schedules/update?id=' . $model->schedule_id);
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['id' => "updateicon_" . $scmodel->scr_id]);
                                } else {
                                    return "";
                                }
                            }
                        }
                    },
                    'delete' => function ($url, $model) {
                        $scmodel = DlStudentCourse::find()->where(['scr_id' => $model->scr_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" && $scmodel->scr_completed_status == "1") {
                                return "";
                            } else {
                                $insid = Yii::$app->user->getId();
                                if ($model->role == 2 && $model->created_by == $insid) {
                                    return Html::a('<span class="glyphicon glyphicon-trash" title="Delete"></span>', ['schedules/delete', 'id' => $model->schedule_id], ['id' => "deleteicon_" . $scmodel->scr_id, "data-pjax" => 0, 'onClick' => 'return confirm("Are you sure you want to delete this schedule?") ', "data-method" => "post"]);
                                } else {
                                    return "";
                                }
                            }
                        }
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
<?php
/* For update the status of the schedule */
$callback = Yii::$app->urlManager->createUrl(['instructor/schedules/statusupdate']);
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