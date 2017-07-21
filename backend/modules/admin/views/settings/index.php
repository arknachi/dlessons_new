<?php

use common\models\DlSettings;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-settings-index">
     <?php
    $form = ActiveForm::begin([
                'id' => 'setting-form',
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
    ]);
    ?>
    <div class="box-body">   
        <?php       
        echo $form->field($model, 'dashboard', [
        'template' => '{label}<div class="col-sm-5 crse_container">{input}<div class=\"errorMessage\">{error}</div></div>'
        ])->checkboxList($dashboard_boxes, [
            'item' => function($index, $label, $name, $checked, $value) {
                $checked = ($checked == 1) ? "checked" : "";
                return "<label class='col-md-12'><input type='checkbox' {$checked} id='{$label}' name='{$name}' value='{$value}'> {$label}</label> <br>";
            },
        ]);
        ?>
    </div>
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
