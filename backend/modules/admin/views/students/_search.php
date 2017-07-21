<?php

use common\models\DlAdminLessons;
use common\models\DlStudentSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlStudentSearch */
/* @var $form ActiveForm */
?>
<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
//            'fieldConfig' => [
//                    'template' => "{label}<div class=\"col-sm-4\">{input}</div>",
//                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
//                ],
        ]);
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="glyphicon glyphicon-search"></i>  Search
                </h3>
                <div class="clearfix"></div>
            </div>
            <section class="content">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <?php echo $form->field($model, 'first_name'); ?>                  
                    </div>
                </div>  
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <?php echo $form->field($model, 'last_name'); ?>                  
                    </div>
                </div>  
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <?php echo $form->field($model, 'student_dob')->textInput(['class' => 'form-control datepicker']); ?>                  
                    </div>
                </div>  
                  <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <?php
                        echo $form->field($model, 'searchlesson')->dropDownList(ArrayHelper::map(DlAdminLessons::find()->andWhere('admin_id = :adm_id and status=1', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'lesson_id', 'lessons.lesson_name'),['prompt'=>'All']);
                        ?>                  
                    </div>
                </div>  
                <div class="col-lg-4 col-md-4">
                    <div class="form-group">
                        <?php //echo $form->field($model, 'student.studentProfile.dob');
                        echo $form->field($model, 'searchstatus')->dropdownList(['1' => 'Not Assigned', '2' => 'Assigned', '3' => 'Registered (paid)', '4' => 'Registered (not paid)'], ['prompt' => 'All']);
                        ?>                  
                    </div>
                </div>  
               
                <div class="col-lg-2 col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>                        
                         <?= Html::submitButton('Search', ['class' => 'btn btn-primary form-control']) ?>
                    </div>
                </div>

            </div>
        </section>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>