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


    <p style="text-align: right;">
        <?= Html::a('Add Schedule', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <h3> Student:  <?php echo $stud_info->first_name . ' ' . $stud_info->last_name; ?> </h3>

    <h3> Lesson:  <?php echo $les_info->lesson_name; ?> </h3>
    
    <h3> Total Hours:  <?php echo $les_info->hours; ?> </h3>
     
    <h3> Remaining Hours:  <?php echo $different; ?> </h3>

    <div>
        <?php
        echo $this->render('schedule_info', [
//            'model' => $model,
            'dataProvider'=>$dataProvider,
        ]);
        ?>
    </div>  