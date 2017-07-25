<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;

$this->title = "Step 4: Payment Confirmation";
$ad = $course->ads;
Yii::$app->view->params['AdminLogo'] = $ad->adminlogo;
?>
<?= $this->render('partial/top_breadcrumb', ['step' => 4]) ?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>Payment Confirmation </h1>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <div class="payment-message">
            <p> <span> Payment Successful! </span> </p>
            <h2> <?php echo $ad->admin->company_name;?> </h2>
            <p> Phone:  <?php echo $ad->admin->cell_phone;?></p>
            <p> Email : <?php echo $ad->admin->email;?></p>
            <p>
                <?php ActiveForm::begin(); ?>
                <?= Html::submitButton('Next', ['class' => 'btn btn-success','name' => 'next','value'=>'1']) ?>
                <?php ActiveForm::end(); ?>
            </p>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
        <?= $this->render('partial/price_view', compact('ad')) ?>
    </div>
</div>