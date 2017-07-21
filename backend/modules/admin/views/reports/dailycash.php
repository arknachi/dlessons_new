<?php

use common\components\Myclass;
use common\models\DlAdmin;
use common\models\DlPaymentSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DlPaymentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Cash Report';
$this->params['breadcrumbs'][] = $this->title;

$startdate = "";
$enddate = "";

$amodel = DlAdmin::findOne(Yii::$app->user->identity->ParentAdminId);

$cost = 0;
$total_studs = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $cost += $val->payment_amount;
        $total_studs++;
    }
    $cost = Yii::$app->formatter->asCurrency($cost, 'USD');
}

$totalcount = $dataProvider->getTotalCount();
?>
<div class="dl-payment-index">
    <?php echo $this->render('_search_dailycash', ['searchModel' => $searchModel]); ?>   
    <?php if ($searchModel->startdate != "" && $searchModel->enddate != "") { ?>
        <?php if ($totalcount > 0) { ?>
            <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-primary pull-right"> <i class="fa fa-print"></i>  Print</a>    
            <div class="col-lg-12 col-md-12">&nbsp;</div>
        <?php } ?> 
        <div id="Getprintval"> 

            <div class='col-lg-12 col-md-12 dailycash'>
                <div class="row">
                    <div class='col-lg-3 col-md-3 col-sm-3'>
                        <?php if ($amodel->logo != ""): ?>                       
                        <div class="row"><img src="/uploads/logos/<?php echo $amodel->logo; ?>" /></div>                        
                        <?php endif ?>
                    </div>  
                    <div class='col-lg-9 col-md-9 col-sm-9'>
                        <div class='pull-right'>
                            <p><?php echo $amodel->company_name ." - ".  $amodel->address1.'&nbsp;'. $amodel->address2." - ".$amodel->city .", ". $amodel->state." &nbsp;". $amodel->zip; ?></p>
                            <p style="text-align: right"><?php echo $amodel->work_phone." | ".$amodel->cell_phone;?></p>
                        </div>
                    </div> 
                </div>
            </div>
            
            <div class="col-lg-12 col-md-12">  
                <div class="row">
                    <?=
                    GridView::widget([
                        'layout' => "<div class='panel panel-primary'>"
                        . "<div class='panel-heading'>"
                        . "<div class='pull-right'>{summary}</div>"
                        . "<h3 class='panel-title'>Daily Cash </h3></div>"
                        . "<div class='panel-body'>"
                        . "<div class='col-lg-12 col-md-12'><div class='row'>"
                        . "<h4>From {$searchModel->startdate} until {$searchModel->enddate} </h4>"
                        . "<h4>Total amount received in this page: <strong>{$cost}</strong></h4>"
                        . "<h4>Total students in this page: <strong>{$total_studs}</strong></h4>"
                        . "</div></div>"
                        . "{items}{pager}</div></div>",
                        'dataProvider' => $dataProvider,
                        'showFooter' => true,
                        'columns' => [
                            //    ['class' => 'yii\grid\SerialColumn'],
                            [
                                'label' => 'Receipt',
                                'attribute' => 'payment_id',
                                'value' => function ($model, $key, $index, $widget) {
                                    return $model->payment_id;
                                },
                                'enableSorting' => false,
                            ],
                            [
                                'label' => 'Student Name',
                                'value' => function ($model, $key, $index, $widget) {
                                    return $model->scr->Studentname;
                                },
                                'enableSorting' => false,
                            ],
                            [
                                'label' => 'DOB',
                                'value' => function ($model, $key, $index, $widget) {
                                    return ($model->scr->student->studentProfile->dob != "") ? Myclass::date_dispformat($model->scr->student->studentProfile->dob) : "-";
                                },
                                'enableSorting' => false,
                            ],
                            [
                                'label' => 'Paymt. Date',
                                'attribute' => 'created_at',
                                'value' => function ($model, $key, $index, $widget) {
                                    return Myclass::date_dispformat($model->created_at);
                                },
                                'enableSorting' => false,
                            ],
                            [
                                'label' => 'Phone',
                                'value' => function ($model, $key, $index, $widget) {
                                    return ($model->scr->student->studentProfile->phone != "") ? $model->scr->student->studentProfile->phone : "-";
                                },
                                'enableSorting' => false,
                                'footer' => "<strong>Total Paid:</strong>",
                            ],
                            [
                                'label' => 'Amount Paid',
                                'attribute' => 'payment_amount',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    // return $model->payment_amount;
                                    return Yii::$app->formatter->asCurrency($model->payment_amount, 'USD');
                                },
                                'enableSorting' => false,
                                'footer' => "<strong>" . $cost . "</strong>",
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>           
    <?php } ?>
</div>
<?php
$js = <<< EOD
$(document).ready(function(){
   
   $(".dailycash").hide();        
   
   $("#print_res").click(function() {
        var startdate = $("#dlpaymentsearch-startdate").val();
        var enddate = $("#dlpaymentsearch-enddate").val();

        $("#startdate_error").hide();    
        $("#enddate_error").hide();

       if(startdate=="")
        {
            $("#startdate_error").show();
            return false;
        }

       if(enddate=="")
        {
            $("#enddate_error").show();
            return false;
        }

        return true;

    });
        
    $("#printdiv").click(function() {   
        var innerContents = document.getElementById("Getprintval").innerHTML;
        var popupWinindow = window.open('', '_blank', 'width=1000,height=700,scrollbars=yes,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="/webpanel/themes/admin/css/print.css"/></head><body onload="window.print()">' + innerContents + '</html>');    popupWinindow.document.close();  
    });      
    
});
EOD;
$this->registerJs($js, View::POS_END);
?>
