<?php

use common\models\DlAdminSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DlAdminSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Subadmins';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-6 col-sm-6 col-lg-12 col-xs-12">
    <div class="x_panel">      
        <p style="text-align: right;">
            <?= Html::a('Add New', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
        <div class="x_content">          
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
                        'label' => 'User Name',
                        'attribute' => 'username',
                        'value' => 'username',
                        'filter' => true,
                        'enableSorting' => true,
                        'contentOptions' => ['style' => 'width:100px;  min-width:150px;  '],
                    ],
                    'email:email',
                    'first_name', 
                    'last_name',
                    [
                        'header' => 'Status',
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if ($model->status)
                                return '<span class="label label-success">Active</span>';
                            else
                                return '<span class="label label-danger">InActive</span>';
                        },
                        'filter' => false,
                        'enableSorting' => true,
                    ],
                    [
                        'label' => 'Last Modified',
                        'attribute' => 'updated_at',
                        'value' => 'updated_at',
                        'filter' => false,
                        'enableSorting' => true,
                        'contentOptions' => ['style' => 'width:40px;  min-width:50px;  '],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>