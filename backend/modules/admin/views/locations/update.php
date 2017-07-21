<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlLocations */

$this->title = 'Update  Location';
$this->params['breadcrumbs'][] = ['label' => 'Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dl-locations-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
