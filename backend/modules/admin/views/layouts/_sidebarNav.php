<?php

use yii\widgets\Menu;
?>
<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left info">
                <p>Hello <?= Yii::$app->user->identity->fullname; ?></p>
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
        $suadminid = Yii::$app->user->id;
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
                ['label' => '<i class="fa fa-image"></i> <span>Ads</span>', 'url' => ['ads/index'], 'active' => ($_controller == 'ads' && $_action == 'index')],
                ['label' => '<i class="fa fa-list"></i> <span>Lessons</span>', 'url' => ['lessons/index'], 'active' => ($_controller == 'lessons' && $_action == 'index')],
                ['label' => '<i class="fa fa-users"></i> <span>Instructors</span>', 'url' => ['instructors/index'], 'active' => ($_controller == 'instructors' && $_action == 'index')],
                ['label' => '<i class="fa fa-calendar"></i> <span>Schedules</span>', 'url' => ['schedules/index'], 'active' => ($_controller == 'schedules' && $_action == 'index')],
                ['label' => '<i class="fa fa-users"></i> <span>Students</span>', 'url' => ['students/index'], 'active' => ($_controller == 'students' && $_action == 'index')],
                ['label' => '<i class="fa fa-dollar"></i> <span>Payments</span>', 'url' => ['payments/index'], 'active' => ($_controller == 'payments' && $_action == 'index')],                
                ['label' => '<i class="fa fa-bar-chart"></i> <span>Reports</span><i class="fa pull-right fa-angle-left"></i>',
                    'url' => "#",                    
                    'options' => ['class' => 'treeview'],                   
                    'items' => [
                        ['label' => '<i class="fa fa-angle-double-right"></i> <span>Payment Reports</span>', 'url' => ['reports/payments']],                        
                        ['label' => '<i class="fa fa-angle-double-right"></i> <span>Instructor Hours</span>', 'url' => ['reports/instructor_hours']],
                        ['label' => '<i class="fa fa-angle-double-right"></i> <span>Cash Report</span>', 'url' => ['reports/daily_cash']],                                                
                    ]
                ],
                ['label' => '<i class="fa fa-globe"></i> <span>Locations</span>', 'url' => ['locations/index'], 'active' => ($_controller == 'locations' && $_action == 'index')],
                ['label' => '<i class="fa fa-gear"></i> <span>Settings</span>', 'url' => ['settings/index'], 'active' => ($_controller == 'settings' && $_action == 'index')],
                ['label' => '<i class="fa fa-users"></i> <span>CSR Management</span>', 'url' => ['subadmin/index'], 'active' => ($_controller == 'subadmin' && $_action == 'index'),'visible'=>( Yii::$app->user->identity->parentid==0)],
                ['label' => '<i class="fa fa-envelope"></i> <span>Mail Templates</span>', 'url' => ['mail/index'], 'active' => ($_controller == 'mail' && $_action == 'index'),'visible'=>( Yii::$app->user->identity->parentid==0)],
            ],
            'submenuTemplate' => "\n<ul class='treeview-menu' role='menu'>\n{items}\n</ul>\n",
        ]);
        ?>
    </section>
</aside>