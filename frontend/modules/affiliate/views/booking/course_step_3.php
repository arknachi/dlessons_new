<?php

use common\components\Myclass;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = "Step 3: Payment";
$ad = $course->ads;
Yii::$app->view->params['AdminLogo'] = $ad->adminlogo;
$step=3;
?>
<?= $this->render('partial/top_breadcrumb', ['step' => 3]) ?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>Payment </h1>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'instructor-form',
                    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'validateOnSubmit' => true,
                    'validateOnChange' => true,
                    'validateOnType' => true,
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-xs-12 col-sm-6 col-md-6 col-lg-6\">{input}<div class=\"errorMessage\">{error}</div></div>",
                        'labelOptions' => ['class' => 'text-left col-xs-12 col-sm-5 col-md-5 col-lg-5'],
                    ],
        ]);
        ?>
        <?= $form->field($spmodel, 'payer_firstname')->textInput() ?>
        <?= $form->field($spmodel, 'payer_lastname')->textInput() ?>
        <?= $form->field($spmodel, 'payer_address1')->textInput() ?>
        <?= $form->field($spmodel, 'payer_address2')->textInput() ?>
        <?= $form->field($spmodel, 'payer_city')->textInput() ?>
        <?= $form->field($spmodel, 'payer_state')->textInput() ?>
        <?= $form->field($spmodel, 'payer_zip')->textInput() ?>
        <hr>  
        <?php echo $form->field($spmodel, 'card_num',[
                 'template' => "{label}<div class=\"col-xs-12 col-sm-6 col-md-6 col-lg-6\">{input}<div class='custom-error wrongnumber'></div></div>"
                ])->textInput(["id" => "cardNumber"]); ?>   
         
        <div class="form-group" id="expiration-date">
            <label class="text-left col-xs-12 col-sm-5 col-md-5 col-lg-5">Card Expiration Date</label>          
            <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
               <?php echo Html::activeDropDownList($spmodel, 'exp_month', Myclass::getMonths(),["class"=>'form-control']); ?>   
            </div>  
            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">  
            Year
             </div> 
            <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">  
                
                 <?php echo $form->field($spmodel, 'exp_year',[
                 'template' => "{input}<div class='custom-error exp_year_error'></div>"
                ])->textInput(["class"=>"form-control numberentry","id" => "exp_year",'placeholder' => '']); ?> 
            </div>  
        </div>  
         <?php echo $form->field($spmodel, 'card_cvv',[
                 'template' => "{label}<div class=\"col-xs-12 col-sm-2 col-md-2 col-lg-2\">{input}</div><div class='col-xs-12 col-sm-6 col-md-6 col-lg-6  custom-error wrongcvv'></div>"
                ])->textInput(["id" => "cvv"]); ?>   
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= Html::submitButton('Pay Now', ['class' => 'btn btn-success pull-right',"id"=>"confirm-purchase"]) ?>
                <?php //Html::submitButton('Pay Later', ['class' => 'btn btn-default btn-danger pull-right','name' => 'scr_pay_later']) ?> 
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
        <?= $this->render('partial/price_view', compact('ad','step')) ?>
    </div>
</div>
<?php
$script = <<< JS
$(function() {
        
   $("#exp_year").attr('maxlength','2');
  
   $('#exp_year').on('change keyup paste', function(){
        var strlen =  $('#exp_year').val().length;       
        if (strlen > 2) {
            var yearstr = $('#exp_year').val();
            var res = yearstr.substr(1, 2); 
            $('#exp_year').val(res);
        }
    });    
        
    var cardNumber = $('#cardNumber');
    var CVV = $("#cvv");
    var confirmButton = $('#confirm-purchase');

    // Use the payform library to format and validate
    // the payment fields.
        
    cardNumber.payform('formatCardNumber');
    CVV.payform('formatCardCVC');
        
    confirmButton.click(function(e) {
      
        var flag = true;
        var isCardValid = $.payform.validateCardNumber(cardNumber.val());
        var isCvvValid = $.payform.validateCardCVC(CVV.val());
        var exp_year = $('#exp_year').val();
        
        $('.wrongnumber').hide();
        $('.wrongcvv').hide();
        $('.exp_year_error').parent().removeClass('has-error');
        
        if (!isCardValid) {           
            $('.wrongnumber').html("Please enter valid card number!");
            $('.wrongnumber').show();
            flag = false;
        }
            
        if($.trim(exp_year).length!=2){  
            $('.exp_year_error').show();           
            $('.exp_year_error').parent().addClass('has-error');
            flag = false;
        }
        
        if (!isCvvValid) {
            $('.wrongcvv').html("Please enter valid cvv value!");
            $('.wrongcvv').show();
            flag = false;
        } 
        
        if(flag)
        {        
            $('.wrongnumber').hide();
            $('.wrongcvv').hide();         
            return true;
        }else{       
            return false;
        }        
    });
        
    $(document).on('keypress','.numberentry',function( e ) { 
        e = e || event;
        if (e.ctrlKey || e.altKey || e.metaKey)
            return;
        var chr = getChar(e);
        if (chr == null)
            return;
        if (chr < '0' || chr > '9') {
            return false;
        }
    });    
});
        
function getChar(event) {
        if (event.which == null) {
            if (event.keyCode < 32)
                return null;
            return String.fromCharCode(event.keyCode) // IE
        }

        if (event.which != 0 && event.charCode != 0) {
            if (event.which < 32)
                return null;
            return String.fromCharCode(event.which)
        }

        return null;
    }        
        
JS;
$this->registerJs($script, View::POS_END);
?>
