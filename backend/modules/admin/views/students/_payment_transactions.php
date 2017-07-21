<?php

use common\models\DlStudent;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudent */
/* @var $form ActiveForm */
?>

<div class="dl-student-form">
    <?=
    GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getDlPayments(),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'payment_amount',
            'payment_type',
            'payment_trans_id',
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'value' => function ($scmodel) {
                    return ($scmodel->payment_status == "0") ? '<span class="label label-danger">Pending</span>' : '<span class="label label-success">Completed</span>';
                },
            ],
            'created_at',
        ],
        'emptyText' => '-',
    ]);
    ?>

</div>
