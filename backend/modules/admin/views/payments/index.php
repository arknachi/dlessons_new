<?php

use common\models\DlPayment;
use common\models\DlPaymentSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DlPaymentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-payment-index">
    <p style="text-align: right;">
        <?= Html::a('Add New', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'payment_id',
            'scr.lesson.lesson_name',
            'scr.student.first_name',
            'scr.student.last_name',
            'payment_amount',
            [
                'attribute' => 'payment_type',
                'format' => 'raw',
                'value' => function ($model) {
                    $ptypes = DlPayment::$payment_types;
                    return ($model->payment_type != "") ? $ptypes[$model->payment_type] : '';
                },
            ],
            [
                'attribute' => 'payment_date',
                'format' => ['date', 'php:m/d/Y'],
                'options' => ['width' => '10%'],
            ],
            'payment_trans_id',
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'value' => function ($model) {
                    return ($model->payment_status == "0") ? '<span class="label label-danger">Pending</span>' : '<span class="label label-success">Paid</span>';
                },
            ],
            'created_at',
            // 'updated_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'buttons' => [
//                    'assign_students' => function ($url, $model) {
//                        $url = Url::toRoute('schedules/assignstudents?id=' . $model->schedule_id);
//                        return Html::a('<span title="Assign Students" class="fa fa-group"></span>', $url);
//                    },
                    'update' => function ($url, $model) {
                        $url = Url::toRoute('payments/update?id=' . $model->payment_id);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
                    },
                    'scheduled_students' => function ($url, $model) {
                        $url = Url::toRoute('schedules/scheduledstudents?id=' . $model->schedule_id);
                        return Html::a('<span title="Scheduled Students" class="glyphicon glyphicon-tasks"></span>', $url);
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
