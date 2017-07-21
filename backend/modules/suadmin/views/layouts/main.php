<?php
/* @var $this View */
/* @var $content string */

use backend\assets\AppAssetSuadmin;
use common\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

AppAssetSuadmin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php  $this->head();  ?>
    </head>
    <body class="nav-md">
        <?php $this->beginBody() ?>
        <div class="container body">
            <div class="main_container">
                <?php echo $this->render('@backend/modules/suadmin/views/layouts/_sidebarNav'); ?>  
                <?php echo $this->render('@backend/modules/suadmin/views/layouts/_headerBar'); ?>    
                <div class="right_col" role="main">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>               
               </div> 
                 <footer>
                    <div class="pull-right">Driving Lessons - Super Admin Management </div>
                    <div class="clearfix"></div>
                </footer>
            </div>
        </div>   

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
