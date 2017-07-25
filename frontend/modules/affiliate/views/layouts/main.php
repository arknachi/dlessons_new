<?php
/* @var $this View */
/* @var $content string */

use common\models\StudentLoginForm;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="container">
            <div class="sitebg">
                <div class="header-cont">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <?php if(isset($this->params['AdminLogo'])): ?>
                            <?= Html::img('@web/'.$this->params['AdminLogo'], ['class'=>'img-responsive']) ?>
                            <?php endif ?>
                        </div>

                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 text-right top-links">
                            <?php if(Yii::$app->user->isGuest): ?>
                            <?= Html::a('Welcome Guest', '#', ['class' => 'btn btn-default btn-success']) ?>
                            <?= Html::a('Sign In', '#', ['class' => 'btn btn-primary', 'data-toggle'=>'modal', 'data-target'=>'#login-modal']) ?>
                            <?php else: ?>
                            <?= Html::a('Welcome '.Yii::$app->user->identity->fullname, ['/affiliate/user/index'], ['class' => 'btn btn-default btn-success']) ?>
                            <?= Html::a('Logout', ['/affiliate/default/logout'], ['class' => 'btn btn-danger']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>

        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="loginmodal-container">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h1>Sign in</h1><br>
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'login-modal-form',
                            'action' => ['/affiliate/default/login'],
                            'enableClientValidation' => true,
                            'enableAjaxValidation' => true,
                            'validateOnSubmit' => true,
                            'validateOnChange' => false,
                            'validateOnType' => false,
                        ]);
                        $model = new StudentLoginForm();
                        ?>
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder' => 'Username'])->label(false) ?>
                        <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>

                            <?// $form->field($model, 'rememberMe')->checkbox() ?>
                            <?// Html::a('Forgot?', ['/affiliate/default/request-password-reset']) ?>
                        <?= Html::submitInput('Login', ['class' => 'login loginmodal-submit', 'name' => 'login-button','type' => 'submit']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>