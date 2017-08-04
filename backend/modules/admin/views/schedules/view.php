<?php

use common\models\DbSchedules;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DbSchedules */

$this->title = "Schedule Info";
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-schedules-view">
    
    <div class="col-sm-8">
        <h4> Student:  <strong><?php echo $stud_info->first_name . ' ' . $stud_info->last_name; ?></strong> </h4>
    <h4> Lesson:  <strong><?php echo $les_info->lesson_name; ?></strong> </h4>
    <h4> Total Hours:  <?php echo $les_info->hours; ?></h4>
    <h4> Remaining Hours:  <?php echo $different; ?></h4>
    </div>
    <div class="col-sm-4">
        <?php if($different){ ?>
    <p style="text-align: right;">
        <?= Html::a('Add Remaining Schedule Hours', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php } ?>
    </div>
    <div class="clearfix"></div>

    
    <div>
        <?php
        echo $this->render('schedule_info', [
//            'model' => $model,
            'dataProvider'=>$dataProvider,
        ]);
        ?>
    </div>  