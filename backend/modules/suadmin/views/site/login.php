<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Sign In';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>   
    <div class="login_wrapper">
        <div class="animate form login_form">         
            <section class="login_content">   
                <?php
                $form = ActiveForm::begin([                          
                            'id' => 'login-form-horizontal',
                            'layout' => 'horizontal',
                            'fieldConfig' => [
                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                                'horizontalCssClasses' => [
                                    'label' => 'col-sm-4',
                                    'offset' => 'col-sm-offset-4',
                                    'wrapper' => 'col-sm-8',
                                    'error' => '',
                                    'hint' => '',
                                ],
                            ],
                ]);
                ?>
                <h1><?= Html::encode($this->title) ?></h1>
                <div><?php echo $form->field($model, 'username')->textInput(['autofocus' => true]); ?></div>
                <div><?php echo $form->field($model, 'password')->passwordInput(); ?></div>
                <div> <?php echo $form->field($model, 'rememberMe')->checkbox(); ?> </div>
                <div><?php echo Html::submitButton('Login', ['class' => 'btn btn-primary submit', 'name' => 'login-button']); ?>
<!--                    <p style="text-align: center"><?php //echo CHtml::link('Lost your password?', array('/suadmin/default/forgotpassword'), array("class" => "reset_pass"))                     ?></p>   -->
                </div>
                <div class="clearfix"></div>
                <div class="separator">
                    <div>
                        <h1><i class="fa fa-car"></i> Driving Lessons!</h1>
                        <p>&copy;2016 All Rights Reserved.</p>
                    </div>

                </div>
                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>
