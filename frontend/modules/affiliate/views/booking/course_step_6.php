<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;

$this->title = "Step 2: Disclaimer / Privacy Page";
$ad = $course->ads;
Yii::$app->view->params['AdminLogo'] = $ad->adminlogo;
?>
<?= $this->render('partial/top_breadcrumb', ['step' => 6]) ?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>Order Completed </h1>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="payment-message">
            <p> <span> Your registration has been completed successfully! </span> </p>
            <p>Our office will contact you within 24 to 48 business hours to schedule your appointment.Thank you.</p>

            <h2> <?php echo $ad->admin->company_name; ?> </h2>
            <p> Phone:  <?php echo $ad->admin->cell_phone; ?></p>
            <p> Email : <?php echo $ad->admin->email; ?></p>            
            <p> <?= Html::a(' My Account', ['/affiliate/user/index'], ['class' => 'btn btn-default btn-success']) ?></p>
        </div>
    </div>
</div>