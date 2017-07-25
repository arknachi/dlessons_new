<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Menu;
$this->title = "Step 5: Basic Information";
$ad = $course->ads;
Yii::$app->view->params['AdminLogo'] = $ad->adminlogo;
?>
<?= $this->render('partial/top_breadcrumb', ['step' => 5]) ?>
<div class="body-cont">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 page-heading">
        <h1> Basic Information </h1>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'instructor-form',
                    'options' => ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data'],
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => false,
                    'validateOnSubmit' => true,
                    'validateOnChange' => true,
                    'validateOnType' => true,
                    'fieldConfig' => [
                        'template' => "{label}<div class=\"col-xs-12 col-sm-6 col-md-6 col-lg-6\">{input}<div class=\"errorMessage\">{error}</div></div>",
                        'labelOptions' => ['class' => 'text-left col-xs-12 col-sm-5 col-md-5 col-lg-5'],
                    ],
        ]);
        ?>
        <?= $form->field($course, 'preferred_days')->dropdownList($course::$preferDays)->label('What is your preferred days?') ?>
        <?= $form->field($course, 'preferred_time')->dropdownList($course::$preferTime)->label('What is your preferred time?') ?>
        <?= $form->field($course, 'additional_infos')->textarea(['rows'=>'6']); ?>
        <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?= Html::submitButton('Next', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 pull-right">
        <?= $this->render('partial/price_view', compact('ad')) ?>
    </div>
</div>