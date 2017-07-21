<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DlInstructors */

$this->title = $model->instructor_id;
$this->params['breadcrumbs'][] = ['label' => 'Dl Instructors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-instructors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->instructor_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->instructor_id], [
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
            'instructor_id',
            'admin_id',
            'username',
            'password',
            'first_name',
            'last_name',
            'email:email',
            'address1',
            'address2',
            'website',
            'city',
            'state',
            'zip',
            'work_phone',
            'cell_phone',
            'notes:ntext',
            'status',
            'remember_token',
            'created_at',
            'modified_at',
            'auth_key',
            'updated_at',
        ],
    ]) ?>

</div>
