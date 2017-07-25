<?php

use common\models\DlStudent;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudent */
/* @var $form ActiveForm */
$this->title = "Edit Profile";
?>

<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1>Edit Profile</h1>
    </div>
    <?php
    if ($profilemodel->dob)
        $profilemodel->dob = Yii::$app->formatter->asDate($profilemodel->dob, 'php:m/d/Y');

    $form = ActiveForm::begin([
                'id' => 'student-form',
                'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                'enableClientValidation' => true,
                'enableAjaxValidation' => false,
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'validateOnType' => true,
                'fieldConfig' => [
                    'template' => "{label}<div class=\"col-sm-8\">{input}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-4 control-label'],
                ],
    ]);
    ?>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">     

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

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

            <?= $form->field($profilemodel, 'dob')->textInput(['maxlength' => true, 'class' => 'form-control datepicker']) ?>

        </div>    
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">  
            
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
        </div> 
    </div>      
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">     
            <div class="form-group">  
                <div class="col-sm-0 col-sm-offset-4">   
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                <a class="btn btn-primary" href="<?php echo Url::toRoute('user/index'); ?>">Cancel</a> 
                 </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>
