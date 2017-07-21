<?php

use common\models\DlInstructors;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

if($model->isNewRecord)
{
    $labelclass = "col-sm-2";
    $inputclass = "col-sm-3";   
    $buttonclass = "col-sm-offset-2";
}else{
    $labelclass = "col-sm-4";
    $inputclass = "col-sm-7";   
    $buttonclass = "col-sm-offset-5";
}    

/* @var $this View */
/* @var $model DlInstructors */
/* @var $form ActiveForm */
$form = ActiveForm::begin([
            'id' => 'instructor-form',
            'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'validateOnType' => true,
            'fieldConfig' => [
                'template' => "{label}<div class='".$inputclass."'>{input}<div class='errorMessage'>{error}</div></div>",
                'labelOptions' => ['class' => $labelclass.' control-label'],
            ],
        ]);
        
        $model->password = null;
?>
<div class="box-body">


    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address2')->textInput(['maxlength' => true]) ?>

    <?php if(!$model->isNewRecord){ echo $form->field($model, 'website')->textInput(['maxlength' => true]);} ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zip')->textInput(['maxlength' => true]) ?>

    <?php  if(!$model->isNewRecord){ echo $form->field($model, 'work_phone')->textInput(['maxlength' => true]); } ?>

    <?= $form->field($model, 'cell_phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->radioList(['1' => 'Enabled', '0' => 'Disabled']) ?>



</div><!-- /.box-body -->
<div class="box-footer">
    <div class="form-group">
        <div class="col-sm-0 <?php echo $buttonclass;?>">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

