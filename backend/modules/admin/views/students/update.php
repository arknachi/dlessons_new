<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlStudent */

$this->title = 'Update Student - '.$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dl-student-update">

    <?= $this->render('_form', [
        'model' => $model,
        'profilemodel' => $profilemodel
    ]) ?>

</div>
