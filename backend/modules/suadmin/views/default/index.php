<?php

use common\models\DlAdmin;
use common\models\DlInstructors;
use common\models\DlLessons;
use common\models\DlStudent;
// $this->context->module->id
// $this->context->action->id
// $this->context->action->uniqueId
// get_class($this->context)

$this->title = "Dashboard";

$clientCount = DlAdmin::find()->count();
$insCount = DlInstructors::find()->count();
$lessCount = DlLessons::find()->count();
$studCount = DlStudent::find()->count();
?>
<div class="row top_tiles">
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-users"></i></div>
                <div class="count"><?php echo $clientCount;?></div>
                <h3>Total Clients </h3>               
            </div>
        </div>      
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-group"></i></div>
                <div class="count"><?php echo $insCount;?></div>
                <h3>Total Instructors</h3>              
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-calendar"></i></div>
                <div class="count"><?php echo $lessCount;?></div>
                <h3>Total Lessons</h3>               
            </div>
        </div>
        <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="tile-stats">
                <div class="icon"><i class="fa fa-graduation-cap"></i></div>
                <div class="count"><?php echo $studCount;?></div>
                <h3>Total Students</h3>               
            </div>
        </div>
    </div>