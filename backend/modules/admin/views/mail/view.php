<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DlMail */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Dl Mails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-mail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mail_id], [
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
            'mail_id',
            'admin_id',
            'name',
            'is_active',
            'sent_to',
            'format',
            'mail_title',
            'mail_body_text:ntext',
            'mail_body_html:ntext',
            'mail_from',
            'mail_from_name',
            'mail_bcc',
        ],
    ]) ?>

</div>
