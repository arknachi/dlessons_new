<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlLessons */

$this->title = 'Create Lesson ';

$this->params['breadcrumbs'][] = ['label' => 'Lessons', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'create';
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">                  
            <div class="x_content"> 
                <?php echo $this->render('_form', ['model' => $model,'almodel' => $almodel]); ?>
            </div>
        </div>
    </div>
</div>
