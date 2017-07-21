<!DOCTYPE html>
<html lang="en-US" class="">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo CHtml::encode($this->title); ?></title>
        <?php
        $themeUrl = $this->themeUrl;
        $cs = Yii::app()->getClientScript();

  //     $cs->registerCssFile($themeUrl . '/lib/bs3/css/bootstrap.css');
        $cs->registerCssFile($themeUrl . '/css/font-awesome/css/font-awesome.css');         
        $cs->registerCssFile($themeUrl . '/css/bootstrap-theme.css');
        $cs->registerCssFile($themeUrl . '/css/AdminLTE.css');
        //$cs->registerCssFile('http://code.ionicframework.com/ionicons/1.5.2/css/ionicons.min.css');      
        $cs->registerCssFile($themeUrl . '/css/custom.css');        
        $cs->registerScript('initial','var basepath = "'.Yii::app()->baseUrl.'";');
        ?>
    </head>
    <body class="skin-blue">
        <header class="header">
            <?php echo CHtml::link(Yii::app()->name, array('/webpanel/'), array('class' => 'logo')); ?>
            <nav class="navbar navbar-static-top" role="navigation"></nav>
        </header>
        <?php echo $content; ?>
    </body>
</html>