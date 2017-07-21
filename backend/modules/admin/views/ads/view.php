<?php

use common\models\DbAds;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DbAds */

$this->title = "Ads Info View";
$this->params['breadcrumbs'][] = ['label' => 'Db Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-ads-view">
    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'lesson.lesson_name',
             [
                'label' => 'Ads Image',
                'format' => 'raw',
                'value' => $model->adimage,
            ],
            [
                'label' => 'Ads Course URL',
                'format' => 'raw',
                'value' => $model->bookingurl,
            ],
            [
                'label' => 'Ads Code',
                'format' => 'raw',
                'value' => $model->adcode,
            ],
            'created_at',
        ],
    ])
    ?>

</div>
