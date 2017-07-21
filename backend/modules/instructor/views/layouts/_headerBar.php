<?php
use yii\helpers\Html;
?>
<header class="header">
    <?php echo Html::a( Yii::$app->user->identity->fullname, ['/instructor/'] , $options = ['class' => 'logo'] ); ?>

    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><?php echo Yii::$app->user->identity->fullname; ?> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <p><?php echo Yii::$app->user->identity->fullname; ?></p>
                        </li>
                        <!-- Menu Body-->
                        <li class="user-body">
                            <div class="col-xs-7 text-center">
                                <?php echo Html::a( 'Change password', ['/instructor/default/changepassword'] , $options = ['class' => ''] ); ?>
                            </div>
                            <div class="col-xs-4 text-center">
                                 <?php // echo Html::a( 'Profile', ['/instructor/default/profile'] , $options = ['class' => ''] ); ?>
                            </div>


                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?php echo Html::a( 'Profile', ['/instructor/default/profile'] , $options = ['class' => 'btn btn-default btn-flat'] ); ?>
                            </div>
                            <div class="pull-right">
                                <?php echo Html::a( 'Sign out', ['/instructor/site/logout'] , $options = ['class' => 'btn btn-default btn-flat'] ); ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>