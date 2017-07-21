<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlLessons */

$this->title = 'Update Lesson ';

$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
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
                <?php echo $this->render('_form', ['model' => $model,]); ?>
            </div>
        </div>
    </div>
</div>
