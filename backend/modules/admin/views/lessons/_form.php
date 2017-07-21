<?php

use common\models\DlAdmin;
use common\models\DlAdminLessons;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlAdmin */
/* @var $form ActiveForm */

$form = ActiveForm::begin([
            'id' => 'lessons-form',
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
    <!--    <div class="form-group field-dladminlessons-lesson_id">
            <label for="dladminlessons-lesson_id" class="col-sm-2 control-label">Lesson name</label>
            <div class="col-sm-5"><?php //echo $model->lessons->lesson_name;     ?></div>
        </div>-->
    <?php
    echo $form->field($model, 'lesson_name')->textInput([]);
    echo $form->field($model, 'lesson_desc')->textarea(['rows' => '6']);
    echo $form->field($almodel, 'price')->textInput([]);
    echo $form->field($model, 'hours')->textInput([]);
    echo $form->field($almodel, 'status')->radioList([1 => 'Enable', 0 => 'Disbaled'])->label('Status');
    ?>
</div><!-- /.box-body -->
<div class="box-footer">
    <div class="form-group">
        <div class="col-sm-0 col-sm-offset-2">   
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
