<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlAdmin */

$this->title = 'Create Client';
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                <?php //echo $this->render('_form', ['model' => $model,'lmodel'=> $lmodel]); ?>
                <?php echo $this->render('_form', ['model' => $model,'cmodel' => $cmodel]); ?>
            </div>
        </div>
    </div>
</div>