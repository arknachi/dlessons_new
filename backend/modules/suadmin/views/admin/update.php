<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlAdmin */

$this->title = 'Update Client';
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<!-- page content -->
<div class="page-title">
    <div class="title_left">
        <h3><?php echo Html::encode($this->title); ?></h3>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">                  
            <div class="x_content"> 
                <?php //echo $this->render('_form', ['model' => $model,'lmodel'=> $lmodel,'selected_lessonids' => $selected_lessonids,'lesson_infos' => $lesson_infos]); ?>
                <?php echo $this->render('_form', ['model' => $model,'cmodel' => $cmodel,'selected_cityids' => $selected_cityids]); ?>
            </div>
        </div>
    </div>
</div>
