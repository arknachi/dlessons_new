<?php

use common\models\DbAds;
use common\models\DlAdminLessons;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DbAds */
/* @var $form ActiveForm */
?>

<div class="db-ads-form">
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
                    'template' => "{label}<div class=\"col-sm-5\">{input}{hint}<div class=\"errorMessage\">{error}</div></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ],
    ]);
    ?>
    <div class="box-body">
        <?= $form->field($model, 'lesson_id')->dropDownList(ArrayHelper::map(DlAdminLessons::find()->andWhere('admin_id = :adm_id', [':adm_id' => Yii::$app->user->identity->ParentAdminId])->all(), 'lesson_id', 'lessons.lesson_name')); ?>
        <?= $form->field($model, 'imageFile')->fileInput(['class' => 'hint-block'])->hint("(Size of the image will be 240 x 80)");?>
        <?php if ($model->image): ?>
            <div class="form-group">
                <?= Html::img(['/file', 'id' => $model->image]) ?>
            </div>
        <?php endif; ?>
    </div> 
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-0 col-sm-offset-2">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
