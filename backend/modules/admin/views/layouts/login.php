<?php
use backend\assets\AppAssetAdmin;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
 
AppAssetAdmin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en-US" class="bg-black">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Client Login</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />        
         <?php $this->head() ?>
    </head>
    <body class="bg-black">
        <?php $this->beginBody() ?>
        <?= Alert::widget() ?>
        <?php echo $content ?>       
         <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>