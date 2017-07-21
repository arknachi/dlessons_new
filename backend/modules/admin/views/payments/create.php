<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DlPayment */

$this->title = 'Add New Payment';
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-payment-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
