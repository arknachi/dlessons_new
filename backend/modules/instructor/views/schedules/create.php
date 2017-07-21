<?php

use common\models\DbSchedules;
use yii2fullcalendar\yii2fullcalendar;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this View */
/* @var $model DbSchedules */

$this->title = 'Create Schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-8 col-sm-8 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php echo $this->render('_form', ['model' => $model]); ?>
             </div>
        </div>
    </div>
</div>