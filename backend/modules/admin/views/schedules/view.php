<?php

use common\models\DbSchedules;
use yii\web\View;

/* @var $this View */
/* @var $model DbSchedules */

$this->title = "Schedule Info";
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="db-schedules-view">


    <h2> Student:  <?php echo $stud_info->first_name . ' ' . $stud_info->last_name; ?> </h2>

    <h2> Lesson:  <?php echo $les_info->lesson_name; ?> </h2>

    <h2> Total Hours:  <?php echo $les_info->hours; ?> </h2>

    <div>
        <?php
//        echo $this->render('schedule_info', [
//            'model' => $model,
//        ]);
        ?>
    </div>  