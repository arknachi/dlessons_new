<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mail Templates';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-mail-index">  
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'sent_to',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->sent_to == 1)
                        return 'Admin';
                    else if ($model->sent_to == 2)
                        return 'Student';
                    else if ($model->sent_to == 3)
                        return 'Instructor';                   
                    else
                        return 'Other Emails';
                },
            ],
            'mail_title',
            'mail_from',
            'mail_from_name',
            'format',
            [
                'attribute' => 'is_active',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->is_active)
                        return '<span class="label label-success">Enabled</span>';
                    else
                        return '<span class="label label-danger">Disabled</span>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]);
    ?>
</div>
