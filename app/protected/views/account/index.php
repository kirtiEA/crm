<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Monitorly</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/landing/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/landing/bootstrap-theme.min.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/landing/main.css">
        <link href='http://fonts.googleapis.com/css?family=Delius' rel='stylesheet' type='text/css'>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/landing/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
        <style>
            .modal-dialog {
                width: 353px;
                height: 450px;
            }
            #username,#password {
                width: 300px;
            }
        </style>
    </head>
    <body>

        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/logo.png" class="eatads-logo"></a>
                </div>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#" id="home_login">Login</a></li>
                </ul>
            </div>
        </div>

        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="container" id="big-banner">
            <div class="hero-lines">
                <h1>Everything You Need to Monitor Outdoor. With Peace of Mind.</h1>
                <p>With Monitorily your field staff can collect time critical data and photos of media, you can plan and manage assignments of your field staff, and your clients can directly see proof of media without you taking the trouble of creating ppts. All that with automated analytics on top.</p>
            </div>
            <!--<div class="signup-form">
                <form class="form-inline" role="form">
                    <div class="form-group">
                      <input type="text" placeholder="cheryl@company.com" class="form-control">
                    </div>
                    <div class="form-group">
                      <input type="text" placeholder="+91" class="form-control country-code">
                    </div>
                    <div class="form-group">
                      <input type="tel" placeholder="optional" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-custom">Sign Up. It's Free.</button>
                </form>
            </div>-->
        </div>

        <div class="container">
            <!-- LOGIN MODAL -->
            <div class="modal fade bs-modal-sm" id="LoginModal" tabindex="-1" role="dialog" aria-labelledby="LoginModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">  
                        <div class="container">
                            <form role="form" class="form-signin" style="padding-bottom:32px;" id="loginForm">
                                <h2 class="form-signin-heading">Please Sign In</h2>
                                <input type="text" autofocus="" required="" placeholder="Username" id="username" class="form-control"><br>
                                <input type="password" required="" placeholder="Password" id="password" class="form-control">
                                <label class="checkbox">
                                    <input type="checkbox" value="remember-me"> Remember me
                                </label>
                                <button type="submit" class="btn btn-primary" id="authSignIn">Sign In</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Example row of columns -->
            <div class="row promises">
                <div class="col-md-3 col-sm-3 divider-vertical">
                    <h2>Monitoring Made Easy</h2>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/landing/promise-icon-1.png">
                    <p>Monitoring sites has never been easier with our simple to use Android App and Web dashboard. No more cumbersome digital cameras.</p>
                </div>
                <div class="col-md-3 col-sm-3 divider-vertical">
                    <h2>User Assignment</h2>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/landing/promise-icon-2.png">
                    <p>Organize your field staff to get only the sites they need to monitor with our intuitive User Assignment feature on the Web Dashboard. No more confusion on who is responsible for which site.</p>
                </div>
                <div class="col-md-3 col-sm-3 divider-vertical">
                    <h2>Instant Alerts</h2>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/landing/promise-icon-3.png">
                    <p>Get instant alerts via Email and SMS when problems are reported. Instant alerts now alerts the relevant party when sites have problems so that they can be rectified swiftly.</p>
                </div>
                <div class="col-md-3 col-sm-3">
                    <h2>Reporting</h2>
                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/img/landing/promise-icon-4.png">
                    <p> With a click of a button generate reports of your field staff activity and identify constant problem sites.</p>
                </div>
            </div>
            <div class="container">
                <div class="row text-center"><h2>With Monitorly monitoring is as easy as</h2></div>
                <div class="row" id="process">
                    <div class="col-md-3">
                        <div class="process-step">   
                            <div class="process-step-rectangle"> 
                                <h2>Create</h2>
                                <p>Campaign and Users</p>
                            </div>
                            <div class="process-step-arrow">
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="process-step">   
                            <div class="process-step-rectangle"> 
                                <h2>Assign</h2>
                                <p>Zones and Sites to Field Staff</p>
                            </div>
                            <div class="process-step-arrow">
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="process-step">   
                            <div class="process-step-rectangle"> 
                                <h2>Collect</h2>
                                <p>Images and Data</p>
                            </div>
                            <div class="process-step-arrow">
                            </div>
                        </div>
                    </div> 
                    <div class="col-md-3">
                        <div class="process-step">   
                            <div class="process-step-rectangle"> 
                                <h2>Report</h2>
                                <p>Performance and Analysis</p>
                            </div>
                            <div class="process-step-arrow">
                            </div>
                        </div>
                    </div> 
                </div>  
                <div class="row landing-footer">
                    <footer>
                        <p>&copy; 2014 EatAds.com - All Rights Reserved Company 2014
                            <span class="footer-links pull-right">
                                <a href="https://www.eatads.com/about-us">About</a>
                                <a href="https://www.eatads.com/contact-us">Contact</a>
                                <a href="https://www.facebook.com/EatAds?ref=br_tf"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/landing/facebook.png"></a>
                                <a href="#"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/landing/linkedin.png"></a>
                            </span>
                        </p>
                    </footer>
                </div>
            </div> 
        </div>
        <!-- /container -->        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/landing/vendor/jquery-1.11.0.min.js"><\/script>')</script>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/landing/vendor/bootstrap.min.js"></script>        

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            $(function() {

                $('#home_login').click(function(e) {
                    console.log('clicked');
                    e.preventDefault();
                    $('#LoginModal').modal('show');
                });

                $('#loginForm').submit(function(e) {
                    e.preventDefault();
                    var usrn = $("#username").val();
                    var pass = $("#password").val();
                    $.ajax({
                        url: "<?php echo Yii::app()->urlManager->createUrl('ajax/login'); ?>",
                        data: {
                            usrn: usrn,
                            pass: pass
                        },
                        async: false,
                        beforeSend: function() {
                            if(usrn && pass) {
                                return true;
                            } else {
                                return false;
                            }
                        },
                        success: function(data) {                            
                            obj = JSON.parse(data);
                            console.log('succ' + obj);
                        }
                    });
                    console.log(loginData);
                });

            });

            (function(b, o, i, l, e, r) {
                b.GoogleAnalyticsObject = l;
                b[l] || (b[l] =
                        function() {
                            (b[l].q = b[l].q || []).push(arguments)
                        });
                b[l].l = +new Date;
                e = o.createElement(i);
                r = o.getElementsByTagName(i)[0];
                e.src = '//www.google-analytics.com/analytics.js';
                r.parentNode.insertBefore(e, r)
            }(window, document, 'script', 'ga'));
            ga('create', 'UA-XXXXX-X');
            ga('send', 'pageview');
        </script>
    </body>
</html>