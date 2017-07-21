<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\DlSuperAdmin;
?>
<!-- page content -->
<div class="page-title">
    <div class="title_left">
        <h3><?php echo Html::encode($this->title); ?></h3>
    </div>
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">                  
            <div class="x_content"> 
                <?php
                $form = ActiveForm::begin([
                            'id' => 'changepassword-form',
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
                    echo $form->field($model, 'oldpass')->passwordInput();
                    echo $form->field($model, 'newpass')->passwordInput(['maxlength' => true]);
                    echo $form->field($model, 'repeatnewpass')->passwordInput(['maxlength' => true]);
                    ?>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <div class="form-group">
                        <div class="col-sm-0 col-sm-offset-2">   
<?= Html::submitButton('Change password',[
                    'class'=>'btn btn-primary'
                ]) ?>
                        </div>
                    </div>
                </div>
<?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>




