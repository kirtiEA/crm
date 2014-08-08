<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content=""/>
        <meta name="author" content="" />

        <title>Monitorly Campaigns</title>
        <title><?php //echo CHtml::encode($this->pageTitle);    ?></title>

        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/fonts.css" rel="stylesheet" />
        <!-- Bootstrap core CSS -->
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.css" rel="stylesheet" />
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-theme.css" rel="stylesheet" />
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/fancyfields.css" rel="stylesheet" type="text/css"/>
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-multiselect.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/modernizr.js"></script><!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
          <![endif]-->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.js"></script>

    </head>

    <body class="full static">
        <div class="wrapper">
            <div class="navbar  navbar-static-top">
                <div class="left"><a href="#" class="logo"></a></div>
                <div class="right" id="authButtons"></div>
            </div>

            <div class="modal fade" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">  
                        <div class="container">
                            <form role="form" class="form-signin">
                                <h2 class="form-signin-heading">Please sign in</h2>
                                <input type="text" autofocus="" required="" placeholder="Username" id="username" class="form-control" /><br>
                                    <input type="password" required="" placeholder="" id="password" class="form-control" />
                                    <label class="checkbox"><input type="checkbox" value="remember-me"> Remember me</label>
                                    <button type="button" class="btn btn-primary" id="authSignIn">Sign In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main">

                <div class="popover right" id="popoverContent" style="display:none;">
                    <div class="arrow"></div>
                    <div class="popover-content">
                        <select multiple="multiple" data-title="Assign to" class="assign" name="mediatypeid[]" id="mediatypeid" style="display:none;">
                            <option value="multiselect-all"> All</option>
                            <option value="1">Rahul</option>
                            <option value="9">Dhruv</option>
                            <option value="4">Nigel</option>
                        </select>
                    </div>
                </div>

                <div class="full">
                    <div class="row head menu">
                        <ul>
                            <li class="mon_menu menu_campaign"><a href="<?php echo Yii::app()->urlManager->createUrl('campaign'); ?>">My Campaigns</a></li>
                            <li class="mon_menu menu_site"><a href="<?php echo Yii::app()->urlManager->createUrl('site'); ?>">My Sites</a></li>
                            <li class="mon_menu menu_user"><a href="<?php echo Yii::app()->urlManager->createUrl('user'); ?>">My Users</a></li>
                            <li class="mon_menu menu_task"><a href="<?php echo Yii::app()->urlManager->createUrl('task'); ?>">My Tasks</a></li>
                            <li class="mon_menu menu_site_mass"><a href="<?php echo Yii::app()->urlManager->createUrl('site/massupload'); ?>">Site Upload</a></li>
                            <li class="mon_menu menu_report"><a href="<?php echo Yii::app()->urlManager->createUrl('reports'); ?>">Reports</a></li>
                        </ul>
                    </div>

                    <?php echo $content; ?>

                </div>
            </div>
            <div id="footer">
                <!-- /container -->
                <div class="navbar navbar-inverse navbar-static-bottom ">

                    <div class="left"><p>© 2014 EatAds.com  - All Rights Reserved</p></div>
                    <div class="right social">
                        <ul class="foot_normal">
                            <li><a href="monitorly_static.html">Terms &amp; Conditions</a></li>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Help</a></li>

                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-multiselect.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/fancyfields-1.2.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/fancyfields.csb.min.js"></script>

    </body>
</html>

<?php /*

  <div class="container" id="page">

  <div id="header">
  <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
  </div><!-- header -->

  <div id="mainmenu">
  <?php $this->widget('zii.widgets.CMenu',array(
  'items'=>array(
  array('label'=>'Home', 'url'=>array('/site/index')),
  array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
  array('label'=>'Contact', 'url'=>array('/site/contact')),
  array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
  array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
  ),
  )); ?>
  </div><!-- mainmenu -->
  <?php if(isset($this->breadcrumbs)):?>
  <?php $this->widget('zii.widgets.CBreadcrumbs', array(
  'links'=>$this->breadcrumbs,
  )); ?><!-- breadcrumbs -->
  <?php endif?>

  <?php echo $content; ?>

  <div class="clear"></div>

  <div id="footer">
  Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
  All Rights Reserved.<br/>
  <?php echo Yii::powered(); ?>
  </div><!-- footer -->

  </div><!-- page -->

  </body>
  </html>
 */ ?>