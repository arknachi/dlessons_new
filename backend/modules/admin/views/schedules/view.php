<?php

use common\models\DbSchedules;
use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;

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
    <?php if($different>0){ ?>
    <h4> Remaining Hours:  <?php echo $different; ?></h4>
    <?php }?>
    <?php if($crs_status) {
        echo "<h4>Course Completed Date : " . Yii::$app->myclass->date_dispformat($crs_status) . "</h4>";
    }?>
    </div>
    <div class="col-sm-4">
        <?php if($different){ ?>
    <p style="text-align: right;">
        <?php
        $url = Url::toRoute(['schedules/create', 'scr_id' => $std_scr_id, 'lesson_id' => $les_info->lesson_id]);
         echo Html::a("Add Remaining Schedule Hours", $url, ['class' => 'btn btn-success']);
        ?>
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