<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DlAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lessons';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-lg-12 col-md-12">
    <div class="row">
        <?php //echo $this->render( '_search', ['model' => $searchModel]); ?>
        <p style="text-align: right;">
            <?= Html::a('Create Lesson', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' =>
            [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'S.No',
                ],
                [
                    'label' => 'Lesson Name',
                    'attribute' => 'lessonnameFilter',
                    'value' => 'lesson_name',
                    'filter' => true,
                    'enableSorting' => true,
                ],
                [
                    'label' => 'Hours',    
                    'attribute' => 'hours',
                    'value' => 'hours',
                ],
                [
                    'label' => 'Price',
                    'attribute' => 'adminlessons.price',
                    'filter' => true,
                    'enableSorting' => true,
                ],
                // 'status',  
                [
                    'attribute' => 'updated_at',
                    'value' => 'updated_at',
                    'filter' => false,
                    'enableSorting' => true,
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}&nbsp;&nbsp;{delete}',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->lesson_id], [
                                        'class' => '',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this lesson with this action.',
                                            'method' => 'post',
                                        ],
                            ]);
                        }
                    ]
                ],
            ],
        ]);
        ?>
    </div>
</div>