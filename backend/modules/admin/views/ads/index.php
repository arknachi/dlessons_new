<?php

use common\models\DbAds;
use common\models\DbAdsSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DbAdsSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Course Ads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-ads-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p style="text-align: right">
        <?= Html::a('Create course Ads', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'ads_id',
            'lesson.lesson_name',
            // 'admin_id',
           [
                'label' => 'Ads Course URL',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->bookingurl;
                },
            ],
            // 'content:ntext',
            'created_at',
            // 'updated_at',
            // 'created_by',
            // 'updated_by',
            // 'isDeleted',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;&nbsp;{delete}',
            ],
        ],
    ]);
    ?>
</div>
