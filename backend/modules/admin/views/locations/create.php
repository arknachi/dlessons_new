<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DlLocations */

$this->title = 'Create Location';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-locations-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
