<?php
/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAssetAdmin;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAssetAdmin::register($this);
//$this->registerCssFile("http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css");
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <!--        
 <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css" rel="stylesheet" type="text/css" /> -->

        <?php $this->head() ?>
    </head>
    <body class="skin-blue">
        <?php $this->beginBody() ?>
        <?php echo $this->render('@backend/modules/admin/views/layouts/_headerBar'); ?> 
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <?php echo $this->render('@backend/modules/admin/views/layouts/_sidebarNav'); ?>  
            <?php echo $content; ?>
        </div>  
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>