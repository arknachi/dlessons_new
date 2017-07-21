<?php

use common\models\DlCity;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DlCity */

$this->title = 'Update City';
$this->params['breadcrumbs'][] = ['label' => 'Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dl-city-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
