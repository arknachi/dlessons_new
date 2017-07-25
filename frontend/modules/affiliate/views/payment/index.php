<?php

use common\components\Myclass;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Payment Transactions";
?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>Payment Transactions</h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">    
        <?=
        GridView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getDlPayments(),              
                'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]]
                    ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'scr.lesson.lesson_name',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        return ($scmodel->scr->lesson->lesson_name);
                    },
                ],
                [
                    'attribute' => 'payment_amount',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        return "$" . $scmodel->payment_amount;
                    },
                ],
                'payment_type',
                'payment_trans_id',
                [
                    'attribute' => 'payment_status',
                    'format' => 'raw',
                    'value' => function ($scmodel) {
                        return ($scmodel->payment_status == "0") ? '<span class="label label-danger">Pending</span>' : '<span class="label label-success">Completed</span>';
                    },
                ],
                [
                    'label' => "Created Date",
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:m/d/Y'],
                ],
            ],
            'emptyText' => '<div style="text-align:center;">No Records.</div>',
        ]);
        ?>     
    </div>
    <p><a class="btn btn-primary" href="<?php echo Url::toRoute('user/index'); ?>">Back</a></p>
</div>