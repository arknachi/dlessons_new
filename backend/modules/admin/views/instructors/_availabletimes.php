<?php

use yii\helpers\Html;
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_content">
            <form id="available-form" name="available-form">
                <table class="table table-striped table-bordered" id="container_input">
                    <thead>
                    <th style="text-align: center;">Date</th>
                    <th style="text-align: center;">Start Time <input type="checkbox" name="start_checkall" id="start_checkall" value="1"></th>
                    <th style="text-align: center;">End Time <input type="checkbox" name="end_checkall" id="end_checkall" value="1"></th>
                    </thead>
                    <?php
                    for ($i = 0; $i < 7; $i++) {
                        $stime = "";
                        $etime = "";
                        $date = strtotime(date("Y-m-d", strtotime($schedule_date)) . " +$i day");

                        $disp_date = date("Y-m-d", $date);

                        if (!empty($exst_avmodel) && isset($exst_avmodel[$disp_date])) {
                            $se_val = $exst_avmodel[$disp_date];
                            $expvals = explode("~", $se_val);

                            $start_time = $expvals[0];
                            $stime = date("h:i a", strtotime($start_time));

                            $end_time = $expvals[1];
                            $etime = date("h:i a", strtotime($end_time));
                        }
                        ?>
                        <tr>
                            <td>
                                <div class="form-group">                   
                                    <div class="col-sm-4">
                                        <?php echo date("m/d/Y", $date); ?>
                                        <input id="available_date" name="available_day[]" value="<?php echo $disp_date; ?>" type="hidden">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">                   
                                    <div class="col-sm-10">
                                        <input id="start_time" class="form-control timepicker starttime" name="start_time[]" value="<?php echo $stime; ?>" type="text">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group">                  
                                    <div class="col-sm-10">
                                        <input id="end_time" class="form-control timepicker endtime" name="end_time[]" value="<?php echo $etime; ?>" type="text">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </table>
                <div class="form-group">
                    <div class="col-sm-0 col-sm-offset-5">
                        <input name="cins_id" type="hidden" value="<?php echo $ins_id; ?>" id="ins_id">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary availabletimes']) ?>
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>