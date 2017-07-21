<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\DlAdminSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lessons';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="col-md-6 col-sm-6 col-lg-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2><?php echo Html::encode($this->title); ?></h2>
            <div class="nav navbar-right">
                <?php echo Html::a('Create Lesson', ['create'], ['class' => 'btn btn-success']) ?>               
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
                        'label' => 'Lesson Name',
                        'attribute' => 'lesson_name',
                        'value' => 'lesson_name',
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
                        'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>