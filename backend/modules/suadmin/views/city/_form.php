<?php

use common\models\DlCity;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlCity */
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
    <?php
    echo $form->field($model, 'city_name')->textInput([]);
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