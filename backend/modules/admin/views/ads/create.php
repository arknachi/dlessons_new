<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DbAds */

$this->title = 'Create Ad';
$this->params['breadcrumbs'][] = ['label' => 'Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-ads-create">    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
