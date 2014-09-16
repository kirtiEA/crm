<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Monitorly</title>
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/application.css" />
        <link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' />

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.10.2.min.map"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-multiselect.js"></script> 
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/application.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/ekko-lightbox.js"></script>        

    </head>
    <style>
        .notification {
            width: 40px;
height: 40px;
background-size: cover;
display: block;
-webkit-border-radius: 100px;
-moz-border-radius: 100px;
-ms-border-radius: 100px;
-o-border-radius: 100px;
border-radius: 100px;
font-size: 1.7em;
        }
        
    </style>
    <body class="full static">

        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">                        
                        <div id="logo">
                            <img src="<?php echo Yii::app()->getBaseUrl() . '/images/logo.png'; ?>"></img>
                        </div>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav tabs">
                        <li class="mon_menu menu_campaign"><a href="<?php echo Yii::app()->urlManager->createUrl('myCampaigns'); ?>">Campaigns</a></li>
                        <li class="mon_menu menu_site"><a href="<?php echo Yii::app()->urlManager->createUrl('site'); ?>">Sites</a></li>
                        <li class="mon_menu menu_vendor"><a href="<?php echo Yii::app()->urlManager->createUrl('vendor'); ?>">Vendors</a></li>
                        <li class="mon_menu menu_user"><a href="<?php echo Yii::app()->urlManager->createUrl('user'); ?>">Users</a></li>
                        <li class="mon_menu menu_task"><a href="<?php echo Yii::app()->urlManager->createUrl('task'); ?>">Tasks</a></li>
                        <li class="mon_menu menu_report"><a href="<?php echo Yii::app()->urlManager->createUrl('reports'); ?>">Reports</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo Yii::app()->user->name; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings</a></li>
                                <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/logout'); ?>">Logout</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <?php 
                                $cnt = JoyUtilities::findUnseenNotificationsCount(Yii::app()->user->cid);
                                if($cnt == 0) {
                                    echo '<div class="notification glyphicon glyphicon-bell"></div>';
                                } else {
                                    echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="fetchNotifications();" ><div class="notification glyphicon glyphicon-bell" style="color: coral;" ></div></a>';
                                }    
                            ?>
                            <ul class="dropdown-menu" id="notificationCenter">
                                <li><a href="#">Settings</a></li>
                                <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/logout'); ?>">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <?php if (Yii::app()->user->hasFlash('success')) { ?>
            <div id="flash-messages" class="navbar navbar-inverse navbar-fixed-top" style="position:relative; z-index:999999;background-color: bisque; ">
                <div class="container">
                    <p><?php echo Yii::app()->user->getFlash('success'); ?></p>
                </div>
            </div>
        <?php } ?>
        <?php if (Yii::app()->user->hasFlash('error')) { ?>
            <div id="flash-messages" class="navbar navbar-inverse navbar-fixed-top" style="position:relative; z-index:999999;background-color: red; ">
                <div class="container">
                    <p><?php echo Yii::app()->user->getFlash('error'); ?></p>
                </div>
            </div>
        <?php } ?>
        <div style="display: none" id="completePath"><?php echo Yii::app()->getBaseUrl(true); ?></div>
        <?php echo $content; ?>        

    </body>
</html>