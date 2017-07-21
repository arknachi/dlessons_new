<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Cities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 col-sm-6 col-lg-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo Html::encode($this->title); ?></h2>
            <div class="nav navbar-right">
                <?php echo Html::a('Add New', ['create'], ['class' => 'btn btn-success']) ?>               
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">          
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,              
                'columns' =>
                [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => 'S.No',
                    ],
                    [
                        'label' => 'City Name',
                        'attribute' => 'city_name',
                        'value' => 'city_name',
                        'filter' => true,
                        'enableSorting' => true,
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
