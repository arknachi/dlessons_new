<?php

use common\models\DlStudent;
use common\models\DlStudentProfile;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model DlStudent */

$this->title = 'Student Infos - '.$model->username;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dl-student-view">
     <div class="col-lg-12 col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a id="a_tab_1" href="#tab_1" data-toggle="tab">Student General Info</a></li>               
                <li><a id="a_tab_2" href="#tab_2" data-toggle="tab">Lessons</a></li>               
                <li><a id="a_tab_3" href="#tab_3" data-toggle="tab">Payment Transactions</a></li>                
            </ul>
            <div class="tab-content">                
                <div class="tab-pane active" id="tab_1">
                <?php
                $hear = DlStudentProfile::$hearAbout;
                $lang = DlStudentProfile::$langList;
        
                echo DetailView::widget([
                    'model' => $model,
                    'attributes' => [           
                        'username',                      
                        'email:email',
                        'first_name',
                        'middle_name',
                        'last_name',           
                        'studentProfile.address1',
                        'studentProfile.address2',
                        'studentProfile.city',
                        'studentProfile.state',
                        'studentProfile.zip',
                        'studentProfile.phone',
                        [
                            'label' => 'Date Of Birth',
                            'format' => 'raw',
                            'value' => ($model->studentProfile->dob!="")? date("m/d/Y",strtotime($model->studentProfile->dob)):"",
                        ],
                        'studentProfile.permit_num',
                        [
                            'label' => 'Language',
                            'format' => 'raw',
                            'value' => ($model->studentProfile->language!="")?$lang[$model->studentProfile->language]:"",
                        ],
                        [
                            'label' => 'Hear about this',
                            'format' => 'raw',
                            'value' => ($model->studentProfile->hear_about_this!="")?$hear[$model->studentProfile->hear_about_this]:"",
                        ],    
                        'studentProfile.referred_by',
                        'studentProfile.payer_firstname',
                        'studentProfile.payer_lastname',
                        'studentProfile.payer_address1',
                        'studentProfile.payer_address2',
                        'studentProfile.payer_city',
                        'studentProfile.payer_state',
                        'studentProfile.payer_zip',                       
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
                </div>
                <div class="tab-pane" id="tab_2">
                    <?php echo $this->render('_purchased_courses', ['model' => $model]);   ?>
                </div>  
                <div class="tab-pane" id="tab_3">
                    <?php echo $this->render('_payment_transactions', ['model' => $model]);?>
                </div>  
            </div>
        </div>    
    </div><!-- ./col -->
</div>
<?php
$js = <<< EOD
    $(document).ready(function(){
        var url = window.location.href;
        var activeTab = url.substring(url.indexOf("#") + 1);       
        $('a[href="#'+ activeTab +'"]').tab('show');
    });
EOD;
$this->registerJs($js, View::POS_END, 'students_tabs');
?>
