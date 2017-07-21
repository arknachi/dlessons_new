<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DbSchedules */

$this->title = 'Update Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php echo $this->render('_form', ['model' => $model,'instructors'=>$instructors]); ?>
            </div>
        </div>
    </div>
</div>