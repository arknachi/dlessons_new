<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DlPayment */

$this->title = $model->payment_id;
$this->params['breadcrumbs'][] = ['label' => 'Dl Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->payment_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->payment_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_id',
            'scr_id',
            'student_id',
            'payment_amount',
            'payment_type',
            'payment_trans_id',
            'payment_status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
