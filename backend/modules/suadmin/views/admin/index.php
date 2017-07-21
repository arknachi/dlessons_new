<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DlAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clients';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-6 col-sm-6 col-lg-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo Html::encode($this->title); ?></h2>
            <div class="nav navbar-right">
                <?php echo Html::a('Create Client', ['create'], ['class' => 'btn btn-success']) ?>               
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <?php //echo $this->render( '_search', ['model' => $searchModel]); ?>
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
                     [
                        'label' => 'Company Name',
                        'attribute' => 'company_name',
                        'value' => 'company_name',
                        'filter' => true,
                        'enableSorting' => true,
                    ],    
                     [
                        'label' => 'Code',
                        'attribute' => 'company_code',
                        'value' => 'company_code',
                        'filter' => true,
                        'enableSorting' => true,
                          'contentOptions' => ['style' => 'width:100px;  min-width:150px;  '],
                    ],  
                    [
                        'label' => 'Cell Phone',
                        'attribute' => 'cell_phone',
                        'value' => 'cell_phone',
                        'filter' => true,
                        'enableSorting' => true,
                    ], 
                    // 'status',  
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