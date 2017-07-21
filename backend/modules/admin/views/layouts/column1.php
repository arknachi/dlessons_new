<?php

use common\widgets\Alert;
use yii\widgets\Breadcrumbs;
$this->beginContent('@app/modules/admin/views/layouts/main.php');
?>

<aside class="right-side">
    <section class="content-header">
        <h1>
            <?php
            if ($this->title !== null) {
                echo $this->title;
            } else {
                echo $this->context->module->id;
                echo ($this->context->module->id !== Yii::$app->id) ? '<small>Module</small>' : '';
            }
            ?>
        </h1>
        <?php
        echo Breadcrumbs::widget([
            'homeLink' => [
                'label' => Yii::t('yii', 'Dashboard'),
                'url' => Yii::$app->homeUrl,
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]);

//        $this->widget('zii.widgets.CBreadcrumbs', array(
//            'links' => $this->breadcrumbs,
//            'tagName' => 'ul', // container tag
//            'htmlOptions' => array('class' => 'breadcrumb'), // no attributes on container
//            'separator' => '', // no separator
//            'homeLink' => '<li><a href="' . Yii::app()->baseUrl . '/webpanel/default/index"><i class="fa fa-home"></i> Home</a></li>', // home link template
//            'activeLinkTemplate' => '<li><a href="{url}">{label}</a></li>', // active link template
//            'inactiveLinkTemplate' => '<li class="active">{label}</li>', // in-active link template
//        ));
        ?>
    </section>

    <section class="content">
        <?= Alert::widget() ?>

        <?php echo $content; ?>
    </section>
</aside>
<?php $this->endContent(); ?>