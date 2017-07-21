<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlMail */

$this->title = 'Update Mail Template';
$this->params['breadcrumbs'][] = ['label' => 'Mail Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dl-mail-update">
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
