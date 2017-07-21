<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Edit Profile';
?>
<!-- page content -->
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'admin-form',
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
                        ])
                ?>
                <div class="box-body">
                    <?php
                    echo $form->field($model, 'company_code')->textInput();

                    echo $form->field($model, 'username')->textInput(['readonly' => 'readonly']);

                    echo $form->field($model, 'company_name')->textInput([]);
                    echo $form->field($model, 'website')->textInput([]);

                    echo $form->field($model, 'address1')->textInput([]);
                    echo $form->field($model, 'address2')->textInput([]);
                    echo $form->field($model, 'city')->textInput([]);
                    echo $form->field($model, 'state')->textInput([]);
                    echo $form->field($model, 'zip')->textInput([]);

                    echo $form->field($model, 'first_name')->textInput(['maxlength' => true]);
                    echo $form->field($model, 'last_name')->textInput(['maxlength' => true]);

                    echo $form->field($model, 'work_phone')->textInput([]);
                    echo $form->field($model, 'cell_phone')->textInput([]);

                    echo $form->field($model, 'email')->textInput([]);
                    
                    echo $form->field($model, 'disclaimer')->textarea(['rows' => '6']);
                    echo $form->field($model, 'privacy')->textarea(['rows' => '6']);


                    echo $form->field($model, 'notes')->textarea();
                    ?>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="form-group">
                        <div class="col-sm-0 col-sm-offset-2">
                            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
