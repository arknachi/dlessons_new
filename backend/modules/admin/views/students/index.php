<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Students';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-student-index">
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'first_name',
            'last_name',
            [
                'attribute' => 'dob',
                'format' => ['date', 'php:m/d/Y'],
                'options' => ['width' => '10%'],
            ],
            'email:email',
            [
                'header' => 'Payment Status',
                'attribute' => 'scr_paid_status',
                'format' => 'raw',
                'value' => function ($model) {
                     if($model['scr_paid_status'] == "0"){
                       $url = Url::to("/myaccount-autologin/".$model['student_id'], true);
                       $pflag = Html::a('<span class="label label-danger">Pay Now</span>', $url,["target"=>"_blank"]);
                     }else if($model['scr_paid_status'] == "1"){
                      $pflag = '<span class="label label-success">Paid</span>';
                     }else if($model['scr_paid_status'] == "2"){
                      $pflag = '<span class="label label-danger">Pending</span>';
                     }

                     return $pflag;
                },
            ],
            'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;&nbsp;{delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        $url = Url::toRoute('students/view?id=' . $model['student_id']."#tab_2");
                        return Html::a('<span title="Student Detailed Information" class="glyphicon glyphicon-eye-open"></span>', $url);
                    },
                    'update' => function ($url, $model) {
                        $url = Url::toRoute('students/update?id=' . $model['student_id']);
                        return Html::a('<span title="Update Student Information" class="glyphicon glyphicon-pencil"></span>', $url);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model['student_id']], [
                                    'class' => '',
                                    'data' => [
                                        'confirm' => 'Are you absolutely sure ? You will lose all the information about this user with this action.',
                                        'method' => 'post',
                                    ],
                        ]);
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
