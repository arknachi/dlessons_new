<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DbAds */

$this->title = 'Update Ad';
$this->params['breadcrumbs'][] = ['label' => 'Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="db-ads-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
