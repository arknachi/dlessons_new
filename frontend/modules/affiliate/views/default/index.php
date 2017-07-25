<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Sample Ads Listing Page';
?>
<div class="row">
    <?php foreach ($ads as $ad): ?>
        <div class="col-xs-12 col-sm-3">
            <div class="thumbnail">
                <?= Html::img("@web/$ad->image") ?>
                <div class="caption">
                    <h4><?= Html::a($ad->lesson->lesson_name, $ad->bookingurl) ?></h4>     
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div><!--/row-->