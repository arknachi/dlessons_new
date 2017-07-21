<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DlStudent */

$this->title = 'Create Dl Student';
$this->params['breadcrumbs'][] = ['label' => 'Dl Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-student-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
