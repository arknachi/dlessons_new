<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-locations-index">
    <p style="text-align: right;">
        <?= Html::a('Create Location', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],            
            'address1',
           // 'address2',
            'city',
            'state',
            // 'country',
            // 'zip',
             'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'isDeleted',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',                
            ],
        ],
    ]); ?>
</div>
