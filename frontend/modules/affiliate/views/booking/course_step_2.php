<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;

$this->title = "Step 2: Disclaimer / Privacy Page";
$ad = $course->ads;
Yii::$app->view->params['AdminLogo'] = $ad->adminlogo;
?>
<?= $this->render('partial/top_breadcrumb', ['step' => 2]) ?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1> Disclaimer/Refund</h1>
        <p><?= nl2br($ad->admin->disclaimer) ?></p>
        <h1> Privacy</h1>
        <p><?= nl2br($ad->admin->privacy) ?></p>
        <div class="row">
            <?php ActiveForm::begin(); ?>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= Html::submitButton('Decline', ['class' => 'btn btn-default btn-danger','name' => 'scr_disclaimer_status','value'=>'0']) ?>
                <?= Html::submitButton('Agree', ['class' => 'btn btn-success pull-right','name' => 'scr_disclaimer_status','value'=>'1']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>