<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "Step 1: Create Account";
Yii::$app->view->params['AdminLogo'] = $ad->adminlogo;
?>
<?= $this->render('partial/top_breadcrumb', ['step' => 1]) ?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1> Create Account </h1>
    </div>
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
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <?= $form->field($smodel, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($smodel, 'password')->passwordInput() ?>
        <?= $form->field($smodel, 'password_repeat')->passwordInput() ?>
        <?= $form->field($smodel, 'first_name')->textInput() ?>
        <?= $form->field($smodel, 'middle_name')->textInput() ?>
        <?= $form->field($smodel, 'last_name')->textInput() ?>
        <?= $form->field($spmodel, 'gender')->radioList($spmodel::$genderList); ?>
        <?= $form->field($smodel, 'email')->textInput() ?>
        <?= $form->field($spmodel, 'address1')->textInput() ?>
        <?= $form->field($spmodel, 'address2')->textInput() ?>
        <?= $form->field($spmodel, 'city')->textInput() ?>
        <?= $form->field($spmodel, 'state')->textInput() ?>
        <?= $form->field($spmodel, 'zip')->textInput() ?>
        <?= $form->field($spmodel, 'phone')->textInput() ?>
        <?= $form->field($spmodel, 'dob')->textInput(['class' => 'form-control datepicker','data-date-format' => 'mm/dd/yyyy','data-date-end-date' => '-5y']) ?>
        <?= $form->field($spmodel, 'permit_num')->textInput()->label('Learners Permit Number') ?>
        <?= $form->field($spmodel, 'language')->dropdownList($spmodel::$langList)->label('Language Preference') ?>
        <?= $form->field($spmodel, 'hear_about_this')->dropdownList($spmodel::$hearAbout)->label('How did you hear about us?') ?>
        <?= $form->field($spmodel, 'referred_by')->textInput() ?>

        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
        <?= $this->render('partial/price_view', compact('ad')) ?>
    </div>
<?php ActiveForm::end(); ?>
</div>