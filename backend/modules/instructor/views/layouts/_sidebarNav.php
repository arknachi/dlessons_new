<?php
use yii\widgets\Menu;
use yii\helpers\Html;
?>
<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left info">
                <p>Hello, <?php echo Yii::$app->user->identity->fullname; ?></p>
                <a href="javascript:void(0)">
                    <i class="fa fa-circle text-success"></i> Online
                </a>
            </div>
        </div>
        <?php
        // Current controller name
        $_controller = Yii::$app->controller->id;
        $_action = Yii::$app->controller->action->id;
        //'htmlOptions' => array('class' => 'sidebar-menu')
        ?>
        <?php
        // Current controller name
        $_controller = Yii::$app->controller->id;
        $_action = Yii::$app->controller->action->id;

        echo Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'encodeLabels' => false,
            'activateParents' => true,
            'activateItems' => true,
            'items' => [
                ['label' => '<i class="fa fa-dashboard"></i> <span>Dashboard</span>', 'url' => ['default/index'], 'active' => ($_controller == 'default' && $_action == "index")],
//                ['label' => '<i class="fa fa-dashboard"></i> <span>Lessons</span>', 'url' => ['lessons/index'], 'active' => ($_controller == 'lessons' && $_action == 'index')],
//                ['label' => '<i class="fa fa-dashboard"></i> <span>Instructors</span>', 'url' => ['instructors/index'], 'active' => ($_controller == 'instructors' && $_action == 'index')],
                  ['label' => '<i class="fa fa-dashboard"></i> <span>Schedules</span>', 'url' => ['schedules/index'], 'active' => ($_controller == 'schedules' && $_action == 'index')],
             //     ['label' => '<i class="fa fa-dashboard"></i> <span>Students</span>', 'url' => ['students/index'], 'active' => ($_controller == 'students' && $_action == 'index')],
//                ['label' => '<i class="fa fa-dashboard"></i> <span>Payments</span>', 'url' => ['payments/index'], 'active' => ($_controller == 'payments' && $_action == 'index')],
//                ['label' => '<i class="fa fa-dashboard"></i> <span>Reports</span>', 'url' => ['reports/index'], 'active' => ($_controller == 'reports' && $_action == 'index')],
            ],
        ]);
        ?>
    </section>
</aside>