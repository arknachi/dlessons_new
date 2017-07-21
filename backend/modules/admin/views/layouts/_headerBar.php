<?php

use common\models\DlAdminCities;
use common\models\DlCity;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$session = Yii::$app->session;
$cityid = $session->get('cityid');
?>
<header class="header">
    <?= Html::a(Yii::$app->user->identity->fullname, ['/admin/'], $options = ['class' => 'logo']); ?>

    <?php // $cmodel = new DlCity(); ?>
    <?php // $form = ActiveForm::begin(); ?>
    <?php // echo $form->field($cmodel, 'city_id')->dropDownList(ArrayHelper::map(DlCity::find()->asArray()->all(), 'city_id', 'city_name')); ?>
    <?php // ActiveForm::end(); ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>        
        <div class="navbar-right">           
            <ul class="nav navbar-nav">
                <?php if (Yii::$app->controller->action->id != "update") { ?>
                    <li>
                        <?php
                        $cmodel = new DlCity();
                        $cmodel->city_id = $cityid;
                        $form = ActiveForm::begin([
                                    'id' => 'city-form',
                                    'fieldConfig' => ['template' => "{input}"]
                        ]);
                        ?>
                        <?php echo $form->field($cmodel, 'city_id')->dropDownList(ArrayHelper::map(DlAdminCities::find()->andWhere('admin_id = :adm_id', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'city_id', 'cities.city_name')); ?>
                        <?php ActiveForm::end(); ?>
                    </li>
                <?php } ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?= Yii::$app->user->identity->fullname; ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <p><?= Yii::$app->user->identity->fullname; ?></p>
                        </li>
                        <!-- Menu Body-->
                        <?php if(Yii::$app->user->identity->parentId==0){ ?>
                        <li class="user-body">
                            <div class="col-xs-7 text-center">
                                <?php echo Html::a('Change password', ['/admin/default/changepassword'], $options = ['class' => '']); ?>
                            </div>
                        </li>
                        <?php }?>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                 <?php /* Profile Edit only for super admin */
                                 if(Yii::$app->user->identity->parentId==0){ ?>
                                <?php echo Html::a('Profile', ['/admin/default/profile'], $options = ['class' => 'btn btn-default btn-flat']); ?>
                                 <?php }else{?>
                                <?php echo Html::a('Change password', ['/admin/default/changepassword'], $options = ['class' => 'btn btn-default btn-flat']); ?>
                                 <?php }?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a('Sign out', ['/admin/site/logout'], $options = ['class' => 'btn btn-default btn-flat']); ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

<?php
$script = <<< JS
  jQuery("#dlcity-city_id").change(function(){
       this.form.submit();       
  });
        
  var cityid = '{$cityid}';
  if(cityid=="")
  $("#city-form").submit();
  
JS;
$this->registerJs($script, View::POS_END);
?>
