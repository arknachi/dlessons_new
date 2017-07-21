<?php

use yii\helpers\Html;
$themeUrl = $this->theme->getBaseUrl();
$noprofilepic = $themeUrl.'/images/no-profile-pic.png';
?>
<div class="top_nav">
    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <?php $themeUrl="";?>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $noprofilepic;?>" alt=""><?php echo Yii::$app->user->identity->username; ?>
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">   
                        <li><?php echo Html::a( 'Edit Profile', ['/suadmin/default/profile'] , $options = [] ); ?>  </li>
                        <li><?php echo Html::a( 'Change password', ['/suadmin/default/changepassword'] , $options = [] ); ?>  </li>
                        <li><?php echo Html::a( '<i class="fa fa-sign-out pull-right"></i> Log Out</a>', ['/suadmin/site/logout'] , $options = [] ); ?></li>  
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</div>      
