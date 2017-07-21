<?php
use yii\widgets\Menu;
use yii\helpers\Html;
$themeUrl = $this->theme->getBaseUrl();
$noprofilepic = $themeUrl.'/images/no-profile-pic.png';
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <?php echo Html::a( '<i class="fa fa-car"></i> <span>Driving Lessons</span>', ['/suadmin/default/index'] , $options = ['class'=>"site_title"] ); ?>
        </div>
        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="<?php echo $noprofilepic; ?>" alt="Driving Lessons" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo Yii::$app->user->identity->username;    ?></h2>
            </div>
        </div>
        <!-- /menu profile quick info -->    
        <br />
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Dashboard</h3>
                <?php
                $suadminid = Yii::$app->user->id;
                // Current controller name
                $_controller = Yii::$app->controller->id;
                $_action = Yii::$app->controller->action->id;

                echo Menu::widget([
                    'options' => ['class' =>'nav side-menu'],
                    'encodeLabels' => false,
                    'activateParents' => true,
                    'activateItems' => true,
                    'items' => [
                        ['label' => '<i class="fa fa-home"></i> <span>Home</span>', 'url' => ['default/index'], 'active' => ($_controller == 'default' && $_action == "index")],
                       // ['label' => '<i class="fa fa-users"></i> <span>Sub Admins</span>', 'url' => ['subadmin'], 'active' => ( $_controller == 'subadmin' && $_action == 'index'), 'visible' => ($suadminid == 1)],
                        ['label' => '<i class="fa fa-users"></i> <span>Client Users</span>', 'url' => ['admin/index'], 'active' => ($_controller == 'admin' && $_action == 'index'), 'visible' => ($suadminid == 1)],
                        ['label' => '<i class="fa fa-list"></i> <span>Cities</span>', 'url' => ['city/index'], 'active' => ($_controller == 'city' && $_action == 'index'), 'visible' => ($suadminid == 1)],
                      //  ['label' => '<i class="fa fa-list"></i> <span>Lessons</span>', 'url' => ['lessons/index'], 'active' => ($_controller == 'lessons' && $_action == 'index'), 'visible' => ($suadminid == 1)],
                      //  ['label' => '<i class="fa fa-bullhorn"></i> <span>Log Activities</span>', 'url' => ['logactivities'], 'active' => $_controller == 'logactivities', 'visible' => ($suadminid == 1)],
                        ['label' => '<i class="fa fa-desktop"></i> <span>Edit Profile</span>', 'url' => ['default/profile'], 'active' => ($_controller == 'default' && $_action == "profile")],
                        ['label' => '<i class="fa fa-sign-out"></i> <span>Logout</span>', 'url' => ['site/logout']],
                        //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ], 
                ]);

                ?>
            </div>          
        </div>
        <!-- /sidebar menu -->
    </div>
</div>