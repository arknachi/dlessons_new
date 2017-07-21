<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DlAdmin */

$this->title = 'Create Subadmin (CSR)';
$this->params['breadcrumbs'][] = ['label' => 'Subadmins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- page content -->
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">                  
            <div class="x_content">               
                <?php echo $this->render('_form', ['model' => $model]); ?>
            </div>
        </div>
    </div>
</div>