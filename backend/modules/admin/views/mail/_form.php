<?php

use common\models\DlMail;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlMail */
/* @var $form ActiveForm */

$this->registerJsFile(
        '@web/js/main.js', ['depends' => [JqueryAsset::className()]]
);
?>
<div class="dl-mail-form">
    <?php
    $form = ActiveForm::begin([
                'id' => 'mail-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'enableClientValidation' => false,
                'enableAjaxValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input} {hint}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    <?= $form->field($model, 'mail_from_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mail_from')->textInput(['maxlength' => true]) ?>   
    <?= $form->field($model, 'mail_bcc')->textInput(['maxlength' => true])->hint("<p class='hint'>List email address to which this email should be sent as hidden copy, separate emails with comma.
</p>") ?>   
    <?= $form->field($model, 'format')->dropDownList(['text/plain' => 'Plain Text', 'text/html' => 'HTML']); ?>
    <?= $form->field($model, 'mail_title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'mail_body_text')->textarea(['rows' => 6]) ?>
    <?=
    $form->field($model, 'mail_body_html')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'advance'
    ])
    ?>
    <?= $form->field($model, 'is_active')->radioList([1 => 'Enable', 0 => 'Disbaled'])->label('Status'); ?>
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$tformat = $model->format;
$script = <<< JS
    var mail_format = '{$tformat}';
    
    showfield(mail_format);
        
    $("#dlmail-format").change(function(){      
        var type = $(this).val();
        showfield(type);
    });
        
    function showfield(type)  {       
        if(type=="text/plain"){
            $(".field-dlmail-mail_body_text").show();            
            $(".field-dlmail-mail_body_html").hide();
        }else{           
            $(".field-dlmail-mail_body_text").hide();            
            $(".field-dlmail-mail_body_html").show();
        }
    }
JS;
$this->registerJs($script);
?>
