<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
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
                                'id' => 'login-form',
                                'options' => ['class' => 'form-horizontal'],
                                'enableClientValidation' => true,
                                'enableAjaxValidation' => true,
                                'validateOnSubmit' => true,
                                'validateOnChange' => false,
                                'validateOnType' => false,
                                'fieldConfig' => [
                                    'template' => "{label}<div class=\"col-xs-12 col-sm-7 col-md-7 col-lg-7\">{input}<div class=\"errorMessage\">{error}</div></div>",
                                    'labelOptions' => ['class' => 'text-left col-xs-12 col-sm-5 col-md-5 col-lg-5'],
                                    'checkboxTemplate' => "<div class=\"text-left col-xs-12 checkbox\">\n{beginLabel}\n{input}\n{labelTitle}\n{endLabel}\n{error}\n{hint}\n</div>"
                                ],
                    ]);
                    ?>
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                    <div class="form-group">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                    <hr />
                    <small><?= Html::a('Forgot password?', ['/affiliate/default/request-password-reset'],['class'=>'small']) ?></small>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>