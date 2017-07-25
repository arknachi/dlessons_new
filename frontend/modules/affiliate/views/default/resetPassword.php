<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="body-cont">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3">
            <div class="panel panel-info" >
                <div class="panel-heading">
                    <div class="panel-title"><?= Html::encode($this->title) ?></div>
                </div>

                <div class="panel-body" >
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'reset-password-form',
                                'options' => ['class' => 'form-horizontal'],
                                'enableClientValidation' => true,
                                'enableAjaxValidation' => true,
                                'validateOnSubmit' => true,
                                'validateOnChange' => false,
                                'validateOnType' => false,
                                'fieldConfig' => [
                                    'template' => "{label}<div class=\"col-xs-12 col-sm-7 col-md-7 col-lg-7\">{input}<div class=\"errorMessage\">{error}</div></div>",
                                    'labelOptions' => ['class' => 'text-left col-xs-12 col-sm-5 col-md-5 col-lg-5'],
                                ],
                    ]);
                    ?>
                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>
                    <div class="form-group">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            &nbsp;
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>