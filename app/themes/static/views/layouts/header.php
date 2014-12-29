<!DOCTYPE html>
<html lang="en">
    <head>                
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EatAds CRM</title>

        <!-- CSS -->
        <?php
        $theme = Yii::app()->theme;
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($theme->getBaseUrl() . '/css/application.css');      
        ?>
        <!--
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="<?php //echo $theme->getBaseUrl();  ?>/js/jquery-ui.min.js"></script>
        <script src="<?php //echo $theme->getBaseUrl();  ?>/js/bootstrap.js"></script>
        <script src="<?php //echo $theme->getBaseUrl();  ?>/js/s_application.js"></script>
        -->

        <?php
        
        //$cs->registerScriptFile($theme->getBaseUrl() . '/js/jquery-1.10.2.min.js');//, CClientScript::POS_BEGIN);
        $cs->registerScriptFile($theme->getBaseUrl() . '/js/jquery-ui.min.js');//, CClientScript::POS_BEGIN);
        $cs->registerScriptFile($theme->getBaseUrl() . '/js/bootstrap.js');//, CClientScript::POS_END);
        $cs->registerScriptFile($theme->getBaseUrl() . '/js/application.js');//, CClientScript::POS_BEGIN);
         ?>
        <style>
            .flag{
                background-image: url("<?php echo $theme->getBaseUrl() . '/images/flags.png';?>"  )
            }
        </style>    
    </head>
    <body>
        <!-- header -->
        <div style="display: none;" id="completePath"><?php echo Yii::app()->getBaseUrl();?></div>
        