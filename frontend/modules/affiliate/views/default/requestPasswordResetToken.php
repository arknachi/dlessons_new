<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
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
                                'id' => 'request-password-reset-form',
                                'options' => ['class' => 'form-horizontal'],
                                'fieldConfig' => [
                                    'template' => "{label}<div class=\"col-xs-12 col-sm-7 col-md-7 col-lg-7\">{input}<div class=\"errorMessage\">{error}</div></div>",
                                    'labelOptions' => ['class' => 'text-left col-xs-12 col-sm-5 col-md-5 col-lg-5'],
                                ],
                    ]);
                    ?>
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                    <div class="form-group">
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            &nbsp;
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                            <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>