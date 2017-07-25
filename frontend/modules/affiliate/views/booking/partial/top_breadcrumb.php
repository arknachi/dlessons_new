<?php

use yii\widgets\Menu;

$items = [
    ['label' => '<div class="number">1</div>Create Account', 'url' => '#'],
    ['label' => '<div class="number">2</div>Disclaimer / Privacy Page', 'url' => '#'],
    ['label' => '<div class="number">3</div>Payment', 'url' => '#','options'=>['class' => 'tab3']],
    ['label' => '<div class="number">4</div>Payment Confirmation', 'url' => '#'],
    ['label' => '<div class="number">5</div>Basic Information', 'url' => '#'],
    ['label' => '<div class="number">6</div>Order Completed', 'url' => '#'],
];

$step = (@$step) ? --$step : 0;
$items[$step]['active'] = true;
?>
<div class="steps-cont">
    <?php
    echo Menu::widget([
        'encodeLabels' => false,
        'items' => $items,
        'options' => ['class' => 'breadcrumb']
    ]);
    ?>
</div>