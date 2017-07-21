<?php

use common\models\DlAdmin;
use common\models\DlCity;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DlAdmin */
/* @var $form ActiveForm */

$city_select_all = array();
$citylist = ArrayHelper::map(DlCity::find()->all(), 'city_id', 'city_name');
foreach ($citylist as $ckey => $cinfo) {
    $select_city_all[] = $ckey;
}

$form = ActiveForm::begin([
            'id' => 'admin-form',
            'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
            'enableClientValidation' => true,
            'enableAjaxValidation' => false,
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'validateOnType' => true,
            'fieldConfig' => [
                'template' => "{label}<div class=\"col-sm-5\">{input}{hint}<div class=\"errorMessage\">{error}</div></div>",
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
            ],
        ]);
?>
<div class="box-body">
    <?php
    echo $form->field($model, 'first_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'last_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'username')->textInput(['maxlength' => true]);
    echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => '',]);
    echo $form->field($model, 'email')->textInput([]);
    echo $form->field($model, 'status')->radioList(['1' => 'Enabled', '0' => 'Disabled']);
    echo $form->field($model, 'notes')->textarea();
    ?>

</div><!-- /.box-body -->
<div class="box-footer">
    <div class="form-group">
        <div class="col-sm-0 col-sm-offset-2">   
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', "id" => "clientsubmit"]) ?>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
?>