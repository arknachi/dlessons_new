<?php

use common\components\Myclass;
use yii\helpers\Html;

?>
<div class="panel panel-primary">
    <div class="panel-heading"> <h3 class="panel-title">Order Details</h3> </div>
    <div class="panel-body">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2"><b><?= $ad->lesson->lesson_name ?></b></td>
            </tr>
            <tr>
                <td>Subtotal </td>
                <td align="right"> <b> <?= Myclass::currencyFormat($ad->adminlesson->price) ?></b></td>
            </tr>
            <tr class="total">
                <td><b>Total  : </b></td>
                <td align="right"><b><?= Myclass::currencyFormat($ad->adminlesson->price) ?></b></td>
            </tr>
        </table>
    </div>
</div>
<?php 
if(isset($step) && $step==3){ ?>
<div class="text-center">
<?= Html::a( 'Pay Later', ['/affiliate/user/index'], ['class' => 'btn btn-default btn-danger','id' => 'scr_pay_later'] );?>
</div>
<?php }?>