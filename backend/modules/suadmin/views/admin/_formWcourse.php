<?php
use common\models\DlAdmin;
use common\models\DlLessons;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<tr class="ncourse prcourse" id="clist-<?php echo $cid; ?>" data-cid="<?php echo $cid; ?>">   
    <?php
    if($model->isNewRecord) 
    { ?>                                          
        <td><?php echo $lmodel->ctitle; ?></td>           
        <td>
          <?php echo Html::activeTextInput($lmodel,"courses[{$cid}][price]",array('class'=>'form-control',"value"=>"","id"=>"AdminCourses_cprice_".$cid,"value"=>0));?>            
        </td>
    <?php 
    }else{ ?>     
       <td> <?php echo $title; ?></td>      
       <td> <?php  echo Html::activeTextInput($lmodel,"courses[{$cid}][price]",array('class'=>'form-control',"value"=>$price));?></td>
   <?php 
    }?>
</tr>