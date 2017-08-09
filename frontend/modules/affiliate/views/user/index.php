<?php

use common\components\Myclass;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "My Account";
?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>My Account</h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 table-responsive">
        <?=
        GridView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $student->getDlStudentCourses(),
                'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
                    ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'lesson.lesson_name',
//                [
//                    'header' => 'Schedule Status',
//                    'attribute' => 'schedule_id',
//                    'format' => 'raw',
//                    'value' => function ($scmodel) {
//                        return ($scmodel->schedule_id == "0") ? '<span class="label label-danger">Not Assigned</span>' : '<span class="label label-success">Assigned</span>';
//                    },
//                ],
//                [
//                    'attribute' => 'schedule.schedule_date',
//                    'format' => ['date', 'php:m/d/Y'],
//                    'options' => ['width' => '10%'],
//                ],
                [
                    'attribute' => 'scr_registerdate',
                    'format' => ['date', 'php:m/d/Y'],
                    'options' => ['width' => '10%'],
                ],
                [
                    'attribute' => 'preferred_days',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        $prefer_days = $scmodel::$preferDays;
                        return ($scmodel->preferred_days != "") ? $prefer_days[$scmodel->preferred_days] : '-';
                    },
                ],
                [
                    'attribute' => 'preferred_time',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        $prefer_days = $scmodel::$preferTime;
                        return ($scmodel->preferred_time != "") ? $prefer_days[$scmodel->preferred_time] : '-';
                    },
                ],
                [
                    'header' => 'Payment Status',
                    'attribute' => 'scr_paid_status',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        if ($scmodel->scr_paid_status == "0"){  
                            
                            $pflag = Html::a( '<span class="label label-danger">Pay Now</span>', ['/course/'.$scmodel->scr_id.'/3'],['class'=>'pay_now_cls']);
                        }else if ($scmodel->scr_paid_status == "1"){
                            $pflag = '<span class="label label-success">Paid</span>';
                        }else if ($scmodel->scr_paid_status == "2"){
                            $pflag = '<span class="label label-danger">Pending</span>';
                        }    

                        return $pflag;
                    },
                ],
                [
                    'attribute' => 'scr_completed_date',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        return ($scmodel->scr_completed_date != "") ? Myclass::dateformat($scmodel->scr_completed_date) : '-';
                    },
                ],
                //  'scr_certificate_serialno',
                [
                    'header' => 'Over All Status',
                    'attribute' => 'scr_completed_status',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        return ($scmodel->scr_completed_status == "0") ? '<span class="label label-danger">Not yet complete</span>' : '<span class="label label-success">Completed</span>';
                    },
                ],
                            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = Url::toRoute('course/view');
                        return Html::a('<span title="Student Detailed Information" class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                                    ],
            ],
            ],
            'emptyText' => '-',
        ]);
        ?>
    </div>

    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 myaccount-relist">
        <a href="<?php echo Url::toRoute('user/update'); ?>">Edit Profile</a>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 myaccount-relist">
        <a href="<?php echo Url::toRoute('payment/index'); ?>">Payment Details</a>
    </div>
       <?php
    if($facebook_url!=""){?>
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 myaccount-relist">
        <a target="_blank" href="<?php echo $facebook_url;?>">Like us on facebook</a>
    </div>
    <?php }?>
</div>