<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Assign Students to the schedule';
$this->params['breadcrumbs'][] = ['label' => 'Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="col-lg-12 col-md-12">
    <div class="row">
        <?php //echo $this->render( '_search', ['model' => $searchModel]); ?>
        <?php
        $form = ActiveForm::begin([
                    'method' => 'post',
        ]);
        ?>
        <p>Schedule Info : <?php echo Yii::$app->myclass->date_dispformat($model->schedule_date) . " - " . Yii::$app->myclass->time_dispformat($model->start_time) . " To " . Yii::$app->myclass->time_dispformat($model->end_time); ?>       
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' =>
            [                
                [
                    'class' => 'yii\grid\CheckboxColumn',                                      
                    'checkboxOptions' => function($model) {
                        return ['value' => $model->scr_id,"class"=>"check"];
                    },
                ],
                [
                    'class' => 'yii\grid\SerialColumn',
                    'header' => 'S.No',
                ],
                 'student.first_name',
                 'student.last_name',
                 'student.email',
                  [
                    'label' => 'Registered At',
                    'attribute' => 'student.created_at',
                    'value' => function($model) {
                        return Yii::$app->myclass->date_dispformat($model->student->created_at);
                    }
                  ],  
             ],
         ]);
                ?>
        <div class="form-group">
            <?= Html::submitButton('Assign Students', ['class' => 'btn btn-primary','id'=>'assign_std_btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$script = <<< JS
    jQuery(document).ready(function ($) { 
        
        $("#assign_std_btn").on('click', function(event) {
            var studcount = $('.check:checked').size();
            if ($('.check:checked').size() == 0){
                alert("Please choose any students.");
                return false;
            }else{
                return true;
            }       
        });
   
        $('.select-on-check-all').on('ifChecked', function(event) {
            $('.check').iCheck('check');
        });
        
        $('.select-on-check-all').on('ifUnchecked', function(event) {
            $('.check').iCheck('uncheck');        
        });
        
        // Removed the checked state from "All" if any checkbox is unchecked
        $('.select-on-check-all').on('ifChanged', function(event){
            if(!this.changed) {
                this.changed=true;
                $('#check-all').iCheck('check');
            } else {
                this.changed=false;
                $('#check-all').iCheck('uncheck');
            }
            $('#check-all').iCheck('update');
        });
        
    });
JS;
$this->registerJs($script, View::POS_END, 'assign_students');
?>