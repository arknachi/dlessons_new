<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-box" id="login-box">
    <div class="header"><?php echo Html::encode($this->title) ?></div>    <?php
    $form = ActiveForm::begin([
                'id' => 'login-form-horizontal',              
                'fieldConfig' => [
                    'template' => "{input}\n{hint}\n{error}\n",
                    'horizontalCssClasses' => [
                        'offset' => 'col-sm-offset-4',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);
    ?> 
    <div class="body bg-gray">
        <?php echo $form->field($model, 'username')->textInput(['autofocus' => true,"placeholder"=>"User Name"]); ?>
        <?php echo $form->field($model, 'password')->passwordInput(["placeholder"=>"Password"]); ?>
        <?php echo $form->field($model, 'rememberMe')->checkbox(); ?>
    </div>
    <div class="footer">                    
        <?php echo Html::submitButton('Login', ['class' => 'btn bg-olive btn-block', 'name' => 'login-button']); ?>
    </div> 
    <?php ActiveForm::end(); ?>
</div>
