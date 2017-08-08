<?php

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

$this->title = "View Schedule Details";
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>View Schedule Details</h1>
    </div>

<div class="dl-student-form">
    <?php
       echo   GridView::widget([
         'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
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
         'hours',
            [
                'attribute' => 'sch_status',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->schedule_id) {
                        $scmodel = DlStudentCourse::find()->where(['scr_id' => $model->scr_id])->one();
                        if ($scmodel) {
                            if ($scmodel->scr_paid_status == "1" &&$model->scr_completed_status == "1") {
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
                            }else if ($scmodel->scr_paid_status == "1" && $model->scr_completed_status == "2") {
                             $sc_stat = '<span class="label label-success">Cancelled</span>';
                        }
                        } else {
                            $sc_stat = '<span class="label label-danger">Not yet complete</span>';
                        }

                        return $sc_stat;
                    }
                },
            ],
        ],
    ]);
    ?>
</div>
 <p><a class="btn btn-primary" href="<?php echo Url::toRoute('user/index'); ?>">Back</a></p>
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
