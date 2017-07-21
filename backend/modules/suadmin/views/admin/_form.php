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
foreach ($citylist as $ckey => $cinfo){
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
    echo $form->field($model, 'company_code')->textInput(['maxlength' => true]);

    echo $form->field($model, 'username')->textInput(['maxlength' => true]);
    echo $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => '',]);

    echo $form->field($model, 'company_name')->textInput([]);
    echo $form->field($model, 'website')->textInput([]);

    echo $form->field($model, 'address1')->textInput([]);
    echo $form->field($model, 'address2')->textInput([]);
    echo $form->field($model, 'city')->textInput([]);
    echo $form->field($model, 'state')->textInput([]);
    echo $form->field($model, 'zip')->textInput([]);

    echo $form->field($model, 'first_name')->textInput(['maxlength' => true]);
    echo $form->field($model, 'last_name')->textInput(['maxlength' => true]);

    echo $form->field($model, 'work_phone')->textInput([]);
    echo $form->field($model, 'cell_phone')->textInput([]);

    echo $form->field($model, 'email')->textInput([]);    
    
    echo $form->field($model, 'facebook_url')->textInput([]);
    
    echo $form->field($model, 'disclaimer')->textarea(['rows' => '6']);
    echo $form->field($model, 'privacy')->textarea(['rows' => '6']);    

    echo $form->field($model, 'imageFile')->fileInput(['class' => 'hint-block'])->hint("(Size of the logo will be 240 x 80)");
    ?>
    <?php if ($model->logo): ?>
        <label for="dladmin-imagefile" class="col-sm-2 control-label">&nbsp;</label>
        <div class="form-group">
            <?php $image_url = Url::to("frontend/web/uploads/logos/".$model->logo, true); ?>
            <img src="<?php echo $image_url;?>">
        </div>
    <?php endif; ?>
    <?php
    echo $form->field($model, 'status')->radioList(['1' => 'Enabled', '0' => 'Disabled']);
    
    echo $form->field($model, 'instructor_schedule_status')->radioList(['1' => 'Yes', '0' => 'No']);
     
    echo $form->field($model, 'notes')->textarea();
    
    if (isset($selected_cityids) && !empty($selected_cityids))
        $cmodel->citylist = $selected_cityids;
    
    if($model->isNewRecord){
        $cmodel->citylist = $select_city_all;
    }

    echo $form->field($cmodel, 'citylist', [
        'template' => '{label}<div class="col-sm-5 crse_container">{input}</div><div class="col-sm-5 col-sm-offset-2 errorMessage" id="citylistError">{error}</div></div>'
    ])->checkboxList($citylist, [
        'item' => function($index, $label, $name, $checked, $value) {
            $checked = ($checked == 1) ? "checked" : "";
            return "<label class='col-md-12'><input type='checkbox' {$checked} id='{$label}' name='{$name}' value='{$value}'> {$label}</label> <br>";
        },
    ]);
//
//    $clist = ArrayHelper::map(DlLessons::find()->all(), 'lesson_id', 'lesson_name');
//    //$selected_keys = ["7"];
//    if (isset($selected_lessonids) && !empty($selected_lessonids))
//        $lmodel->clist = $selected_lessonids;
//
//    echo $form->field($lmodel, 'clist', [
//        'template' => '{label}<div class="col-sm-5 crse_container">{input}<div class=\"errorMessage\">{error}</div></div>'
//    ])->checkboxList($clist, [
//        'item' => function($index, $label, $name, $checked, $value) {
//            $checked = ($checked == 1) ? "checked" : "";
//            return "<label class='col-md-12'><input type='checkbox' {$checked} id='{$label}' name='{$name}' value='{$value}'> {$label}</label> <br>";
//        },
//    ]);
    ?>
        <div class="form-group" style="display:none">
        <label class='col-sm-2'></label>
        <div class="col-sm-8">
            <table class="table table-bordered table-condensed" id="website_course">
                <tbody>
                    <tr>
                        <th style="width: 80%">Course Title</th>                                           
                        <th style="width: 20%">Price</th>                                            
                    </tr>
                    <?php
                    if (!($model->isNewRecord)) {
                        if (!empty($lesson_infos)) {
                            foreach ($lesson_infos as $k => $linfos) {
                                echo $this->render('_formWcourse', ['model' => $model, 'lmodel' => $lmodel, 'cid' => $k, 'title' => $linfos['lesson_name'], 'price' => $linfos['price']]);
                            }
                        }
                    }
                    ?>    
                </tbody></table>
        </div>
    </div> 
</div><!-- /.box-body -->
<div class="box-footer">
    <div class="form-group">
        <div class="col-sm-0 col-sm-offset-2">   
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',"id"=>"clientsubmit"]) ?>
        </div>
    </div>
</div>
<?php
ActiveForm::end();

$callback = Yii::$app->urlManager->createUrl(['suadmin/admin/ajaxcreate']);
$script = <<< JS
    jQuery(document).ready(function () {    
        
        $('#clientsubmit').on('click', function() {
            $('#citylistError').html("");
            var citieslen =  $('[name="DlAdminCities[citylist][]"]:checked').length;
            if(citieslen==0){
                $('#citylistError').html("Please select atleast one city!!");
                return false;
            }           
        });
        
        $('input[type=checkbox]').on('change', function() {
            var cid     = $(this).val();     
            var ctitle  = $(this).attr('id');          
            if($(this).prop('checked'))
            { 
                $.ajax({
                    url  : "{$callback}",
                    type : "POST",
                    dataType : "json",
                    data: {
                      id: cid,
                      label: ctitle,
                      type:'normal'  
                    },
                    success: function(data) {
                      $("#website_course tbody").append(data.html);
                    }
                });                     
            }else{                 
                $("#clist-"+cid).find("td").fadeOut(1000,function()
                {
                    $(this).parent().remove();
                });   
            } 
        });
    });
JS;
$this->registerJs($script, View::POS_END);
?>