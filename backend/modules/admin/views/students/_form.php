<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DlStudent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dl-student-form">

    <?php    
    $form = ActiveForm::begin([
                'id' => 'student-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-5\">{input}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'gender')->radioList(['M' => 'Male', 'F' => 'Female']) ?>
    
    <?= $form->field($profilemodel, 'address1')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'address2')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'city')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'state')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'zip')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'phone')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'dob')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'permit_num')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'language')->dropDownList($profilemodel::$langList); ?>
   
    <?= $form->field($profilemodel, 'hear_about_this')->dropDownList($profilemodel::$hearAbout); ?>
    
    <?= $form->field($profilemodel, 'referred_by')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_firstname')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_lastname')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_address1')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_address2')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_city')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_state')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($profilemodel, 'payer_zip')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'status')->radioList(['1' => 'Enabled', '0' => 'Disabled']) ?>
  
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
