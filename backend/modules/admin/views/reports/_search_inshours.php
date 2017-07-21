<?php

use backend\modules\admin\controllers\ReportsController;
use common\models\DlInstructors;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this ReportsController */
/* @var $form CActiveForm */
?>
<div class="col-lg-12 col-md-12">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="fa fa-search"></i> Search
                </h3>
                <div class="clearfix"></div>
            </div>
            <section class="content">
                <div class="row">
                    <?php
                    $form = ActiveForm::begin([
                                'action' => ['reports/instructor_hours'],
                                'method' => 'get',
                    ]);
                    ?>
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">                        
                            <?php
                            echo $form->field($searchModel, 'instructor_id')->dropDownList(
                                        ArrayHelper::map(DlInstructors::find()->orderBy("first_name ASC")->andWhere('admin_id = :adm_id and status=1', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'instructor_id', function($model, $defaultValue) {
                                        return $model->first_name . " " . $model->last_name;
                                    }), ['prompt' => 'All'])
                            ?>                                                                                    
                        </div>
                    </div> 

                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php echo Html::activeLabel($searchModel, 'startdate'); ?>
                            <div class="input-group">
                                <span class="input-group-addon">  <i class="fa fa-calendar"></i></span>
                                <?php echo $form->field($searchModel, 'startdate')->textInput(['class' => 'form-control datepicker'])->label(""); ?>    
                            </div>    
                            (MM/DD/YYYY)
                            <div style="display: none;" id="startdate_error" class="errorMessage">Please select start date.</div>
                        </div>
                    </div> 
                    <div class="col-lg-3 col-md-3">
                        <div class="form-group">
                            <?php echo Html::activeLabel($searchModel, 'enddate'); ?>
                            <div class="input-group">
                                <span class="input-group-addon">  <i class="fa fa-calendar"></i></span>
                                <?php echo $form->field($searchModel, 'enddate')->textInput(['class' => 'form-control datepicker'])->label(""); ?>    
                            </div>    
                            (MM/DD/YYYY)
                            <div style="display: none;" id="enddate_error" class="errorMessage">Please select end date.</div>
                        </div>
                    </div>                    
                    <div class="col-lg-2 col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <?= Html::submitButton('Filter', ['class' => 'btn btn-primary form-control', "id" => 'print_res']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </section>

        </div>
    </div>
</div>


