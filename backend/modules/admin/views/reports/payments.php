<?php

use common\components\Myclass;
use common\models\DlPaymentSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DlPaymentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;

$startdate = "";
$enddate = "";

$cost = 0;
if (!empty($dataProvider->getModels())) {
    foreach ($dataProvider->getModels() as $key => $val) {
        $cost += $val->payment_amount;
    }
}

$totalcount = $dataProvider->getTotalCount();
?>
<div class="dl-payment-index">
    <?php echo $this->render('_search_paymentreport', ['searchModel' => $searchModel]); ?>   
    <?php 
    if($searchModel->startdate!="" && $searchModel->enddate!=""){ ?>
     <?php
        if ($totalcount > 0) { ?>
        <a href="javascript:void(0);" id="printdiv" class="btn m-b-xs  btn-primary pull-right"> <i class="fa fa-print"></i>  Print</a>    
        <div class="col-lg-12 col-md-12">&nbsp;</div>
    <?php } ?> 
    <div id="Getprintval"> 
        <div class="col-lg-12 col-md-12">
            <div class="row">
                <?=
                GridView::widget([
                    'layout' => "<div class='panel panel-primary'>"
                    . "<div class='panel-heading'>"
                    . "<div class='pull-right'>{summary}</div>"
                    . "<h3 class='panel-title'>Payment Report</h3></div>"
                    . "<div class='panel-body'><h3>Payment Received From {$searchModel->startdate} until {$searchModel->enddate} </h3> <h4>Total amount received in this page: <strong>{$cost}</strong></h4>  {items}{pager}</div></div>",
                    'dataProvider' => $dataProvider,
                    //'showFooter' =>true,                      
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'scr.lesson.lesson_name',
                        'scr.student.first_name',
                        'scr.student.last_name',
                        [
                            'attribute' => 'payment_amount',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->payment_amount;
                            },
                            'enableSorting' => false,
                        ],
                        [
                            'attribute' => 'payment_type',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->payment_type;
                            },
                            'enableSorting' => false,
                        ],
                        [
                            'attribute' => 'payment_trans_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->payment_trans_id;
                            },
                            'enableSorting' => false,        
                        ],                    
                        [
                            'label' => 'Payment Date',
                            'attribute' => 'created_at',
                            'value' => function ($model, $key, $index, $widget) {
                                return Myclass::date_dispformat($model->created_at);
                            },
                            'enableSorting' => false,        
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
        var popupWinindow = window.open('', '_blank', 'width=700,height=700,scrollbars=yes,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
        popupWinindow.document.open();
        popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="/webpanel/themes/admin/css/print.css" /></head><body onload="window.print()">' + innerContents + '</html>');    popupWinindow.document.close();  
    });      
    
});
EOD;
$this->registerJs($js, View::POS_END);
?>
