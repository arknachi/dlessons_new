<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DlAdmin */

$this->title = $model->admin_id;
$this->params['breadcrumbs'][] = ['label' => 'Dl Admins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-admin-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->admin_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->admin_id], [
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
            'admin_id',
            'username',
            'password',
            'email:email',
            'field1',
            'field2',
            'field3',
            'field4',
            'client_name',
            'domain_url:url',
            'status',
            'remember_token',
            'created_at',
            'modified_at',
            'auth_key',
        ],
    ]) ?>

</div>
