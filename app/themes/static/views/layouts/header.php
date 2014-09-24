<!DOCTYPE html>
<html lang="en">
    <head>                
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Monitorly</title>

        <!-- CSS -->
        <?php
        $theme = Yii::app()->theme;
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile($theme->getBaseUrl() . '/css/application.css');
        $cs->registerCssFile($theme->getBaseUrl() . '/css/intlTelInput.css');
        $cs->registerCssFile($theme->getBaseUrl() . '/css/phonenumber.css');
        
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
        $cs->registerScriptFile($theme->getBaseUrl() . '/js/intlTelInput.js');//, CClientScript::POS_BEGIN);
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
        <nav class="navbar navbar-default navbar-dark" id="header_nav" role="navigation">
            <div class="container-fluid">

                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo Yii::app()->getBaseUrl(); ?>">
                        <div class="logo-on-darkbcg" id="static_logo"></div>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
						<?php if(Yii::app()->controller->id == 'account' && Yii::app()->controller->action->id == 'signup') {
		                        if(Yii::app()->user->isGuest) { ?>
		                            <li><a href="#" data-toggle="modal" data-target="#modal-login">Already have an account? Login</a></li>
		                            
		                        <?php } else { ?>
		                            <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/logout'); ?>">Logout</a></li>
		                        <?php }
						} else { ?>
	                        <li class="phone1"><span class="glyphicon glyphicon-phone-alt">&nbsp;</span>+91 11 4132 0334</li>
							<li><a href="https://play.google.com/store/apps/details?id=com.monitorly.monitorlyv05" target="_blank">Download App</a></li>
	                        <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/contactus'); ?>">Contact Us</a></li>
	                        <?php if(Yii::app()->user->isGuest) { ?>
	                            <li><a href="#" data-toggle="modal" data-target="#modal-login">Login</a></li>
	                            <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/signup'); ?>">Sign Up</a></li>
	                        <?php } else { ?>
	                            <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/logout'); ?>">Logout</a></li>
	                        <?php }
						} ?>
                        
                        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <!-- end of header -->