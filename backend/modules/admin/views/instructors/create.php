<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlInstructors */

$this->title = 'Create Instructor';
$this->params['breadcrumbs'][] = ['label' => 'Instructors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php echo  $this->render('_form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</div>