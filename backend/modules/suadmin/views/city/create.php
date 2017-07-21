<?php

use common\models\DlCity;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model DlCity */

$this->title = 'Create City';
$this->params['breadcrumbs'][] = ['label' => 'Cities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-city-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
