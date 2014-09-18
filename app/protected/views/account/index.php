<!DOCTYPE html5>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Monitorly</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/s_application.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/intlTelInput.css">
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/phonenumber.css">
        
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/s_application.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/intlTelInput.js"></script>
            
    </head>

    <body>
        
        <!-- login modal -->

        <div class="modal fade modal-app" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/login.png"> &nbsp;Login to Your Monitorly Account</h3>
                    </div>
                    <div class="modal-body">
                        <div class="form-wrap">
                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'login-form',
                                //'action' => Yii::app()->getBaseUrl() .  '/account/login',   
                                'enableClientValidation' => true,
                                'clientOptions' => array(
                                    'validateOnSubmit' => true,
                                ),
                            ));
                            ?>  
                            <div class="form-group">
                                <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'placeholder' => 'Email', 'id' => 'email')); ?>
                                <?php echo $form->error($model, 'email'); ?>
                            </div>
                            <div class="form-group">
                                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password')); ?>
                                <?php echo $form->error($model, 'password'); ?>
                            </div>
                            <?php echo CHtml::submitButton('Login', array('class' => 'btn btn-primary', 'id' => '_submit')); ?>&nbsp;
                            <a href="#" data-dismiss="modal">Cancel</a>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" data-toggle="modal" id="forgotpassword">Forgot Password?</a> 
                        <a href="signup.html" class="pull-right">Don't have an account? <b>Sign Up</b></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of login modal -->

        <!-- reset password modal -->

        <div class="modal fade modal-app" id="modal-resetpassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title">Reset Your Password</h3>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" id="reset_warning"><span id="reset_err_msg"></span></div>
                        <div class="form-wrap" id="reset_modal_form">
                            <h4>Provide your new password below.</b></h4>
                            <br>
                            <form class="form">
                                <div class="form-group">
                                    <div class="form-group">
                                        <input class="form-control" type="password" id="reset_password" placeholder="New Password">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="password" id="reset_confirm_password" placeholder="Re-type New Password">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" id="reset_submit">Done</button>&nbsp;
                                <a href="#" data-dismiss="modal">Cancel</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of reset password modal -->

        <!-- forgot password modal -->

        <div class="modal fade modal-app" id="modal-forgotpassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h3 class="modal-title">Forgot Password?</h3>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="forgot_modal_alert" style="display: none;"></div>
                        <div class="form-wrap" id="forgot_modal_form">
                            <h4>Don't worry. <b>What's your email?</b></h4>
                            <br>
                            <h5>We will mail you a link to quickly reset it.</h5>
                            <br>
                            <form class="form">
                                <div class="form-group">
                                    <input class="form-control" type="email" id="forgot_email" placeholder="Email">
                                </div>
                                <button type="button" class="btn btn-primary" id="forgot_submit">Okay</button>&nbsp;
                                <a href="#" data-dismiss="modal">Cancel</a>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">                         
                        <a href="signup.html" class="pull-right">Don't have an account? <b>Sign Up</b></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end of forgot password modal -->



        <!-- header -->

        <nav class="navbar navbar-default navbar-dark" role="navigation">
            <div class="container-fluid">

                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.html">
                        <div id="logo"></div>
                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="phone1"><span class="glyphicon glyphicon-phone-alt">&nbsp;</span>+91 11 4132 0334
                        </li>
                        <li><a href="pricing.html">Pricing</a></li>
                        <li><a href="contactus.html">Contact Us</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#modal-login">Login</a></li>
                        <li><a href="signup.html">Sign Up</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

        <!-- end of header -->



        <div class="container-fluid content-wrapper">

            <!-- hero section -->
            <div class="row hero-section">
                <div class="col-md-12">
                    <h1>Tamper-proof, Efficient OOH Monitoring</h1>
                    <h3>Save costs and delight clients by sharing certified OOH campaign photos online, instantly using Monitorly mobile app</h3>
                    <h4><span class="dashed-line"></span> First 100 Photos Free <span class="dashed-line"></span></h4>
                    <?php if (Yii::app()->user->hasFlash('success')) { ?>
            <div id="flash-messages" class="alert alert-success alert-dismissible">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                
            </div>
        <?php } ?>
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'vendor_subscription',
                        'action' => 'account/createvendor',
                        //'enableClientValidation' => true,
                    //    'clientOptions' => array(
                    //        'validateOnSubmit' => true,
                    //    ),
                        'htmlOptions' => array(
                            'class' => 'form-inline',
                        ),
                            ));
                    ?>
<!--                        <input type="email" class="form-control" placeholder="Email">-->
                            <?php echo $form->emailField($modelSub, 'email', array('class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email' )); ?>                            
                            <?php echo $form->error($modelSub, 'email'); ?>
<!--                        <input type="text" class="form-control" placeholder="Company Name">-->
                        <?php echo $form->textField($modelSub, 'companyname',array('class' =>'form-control ', 'placeholder' => 'Company Name')); ?> 
                        <input type="hidden" value="" id="vendor-ac-id">
                        <?php echo $form->error($modelSub, 'companyname'); ?>
<!--                        <input type="text" class="form-control" placeholder="+91">-->
<!--                        <input  class="form-control intl-tel-input" placeholder="Mobile" id="mobile-number" type="tel">-->
                        <?php echo $form->textField($modelSub, 'phonenumber', array('max-length' => '10', 'class' => 'form-control intl-tel-input', 'placeholder' => 'Mobile', 'type' => 'tel', 'id' => 'mobile-number')); ?>                            
                        <?php echo $form->error($modelSub, 'phonenumber'); ?>
                        <br><br>
                        <?php echo $form->hiddenField($modelSub, 'nid',array('value'=>$nid, 'id' =>'nid'));?>
                        <?php echo CHtml::submitButton('Sign Up for Free', array('class' => 'save btn btn-primary btn-primary-lg', 'id' => '_submit')); ?>
<!--                        <button class="btn btn-primary btn-primary-lg">Sign Up for Free</button>-->
                        <br>
                        <h5>No Credit Card required</h5>
                        <h5>By signing-up you agree to <a href="terms.html" target="_blank">Terms &amp; Conditions</a></h5>
                    <?php $this->endWidget(); ?>
                    <div><a class="pricing-link" href="pricing.html">Know More About Pricing &amp; Plan</a></div>
                </div>
            </div>
            <!-- end of hero section -->

            <!-- process steps in monitorly -->
            <div class="row process-steps">
                <div class="col-md-12">
                    <h2>5 Steps to Take All Your Monitoring Pain Away</h2>
                    <img class="img-responsive" src="<?php echo Yii::app()->request->baseUrl; ?>/images/process-steps.png">
                </div>
            </div>
            <!-- end of process steps in monitorly  --> 

            <!-- benefits -->
            <div class="row benefits">
                <div class="col-md-12">
                    <hr>
                    <div class="row testimonial">
                        <div class="col-md-2">
                            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/rongraham.png">
                        </div>
                        <div class="col-md-10">
                            <p>"Proof of Posting &amp; Monitoring is a necessity but Monitorly makes it automated and dynamic for media owners and agencies to use and advertisers to review. This is a big step forward."</p>
                            <p>Ron Graham, Media On The Go</p>
                        </div>
                    </div>
                    <hr>
                    <h1>How Monitorly Does It?</h1>
                    <div class="row">
                        <div class="col-md-5">
                            <ul>
                                <li>
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img-dummy.png">
                                    <h2>Build Trust</h2>
                                    <p>Get confidence in the campaign photos with timestamped, geo-tagged, certified images</p>
                                </li>
                                <li>
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img-dummy.png">
                                    <h2>Manage Monitoring Easily</h2>
                                    <p>Create and manage monitoring plan and assign tasks easily using our online dashboard</p>
                                </li>
                                <li>
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img-dummy.png">
                                    <h2>Share Reports</h2>
                                    <p>Share online interactive report or generate a PDF report instantly in our Reports section</p>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-5 col-md-offset-2">
                            <ul>
                                <li>
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img-dummy.png">
                                    <h2>Report in Seconds</h2>
                                    <p>Upload photos from field and auto-generate reports to reduce reporting time to few seconds</p>
                                </li>
                                <li>
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img-dummy.png">
                                    <h2>Get Problem Alerts</h2>
                                    <p>Receive instant email alerts with problem details if there’s problem with any site</p>
                                </li>
                                <li>
                                    <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/img-dummy.png">
                                    <h2>Operate Without Internet</h2>
                                    <p>Use the app seamlessly even with no network connectivity using our auto-sync feature</p>
                                </li>
                            </ul>
                        </div>
                        <div class="row pricing-preview">
                            <div class="col-md-12">
                                <h2>No Nonsense Pay-As-You-Go Pricing</h2>
                                <br>
                                <h4>Get your free 100 photos when you sign-up</h4>
                                <br>
                                <h4><a href="pricing.html">Know More About Pricing and Plan</a></h4>
                                <br>
                                <button class="btn btn-primary btn-primary-lg js-signup-btn-scrolltop">Sign Up for Free</button>
                                <h5>No Credit Card required</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of benefits  -->

            <!-- contact details -->
            <div class="row contact-details-wrap">
                <div class="col-md-12">
                    <div class="contact-details">
                        <h2>Even More Curious, Approach Us Anytime.</h2>
                        <div class="contact-info pull-left">
                            <span class="glyphicon glyphicon-phone-alt"></span>  +91 11 4132 0334 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:sales@eatads.com"><span class="glyphicon glyphicon-envelope"><span class="contact-info-email">sales@eatads.com</span></a></span>  
                        </div>
                        <div class="social-icons pull-right">
                            <a href="https://www.linkedin.com/company/2128549" target="_blank"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/linkedin.png">&nbsp;</a>
                            <a href="https://www.facebook.com/pages/EatAds/222452511283884" target="_blank"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/facebook.png"></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end of contact details  -->

            <!-- footer -->
            <nav class="navbar navbar-default footer">
                <div class="container-fluid">

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li><a href="https://www.eatads.com/">EatAds.com</a></li>
                            <li><a href="pricing.html">Pricing</a></li>
                            <li><a href="terms.html">Terms &amp; Conditions</a></li>
                            <li><a href="contactus.html">Contact Us</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li><h5 class="pull-right">Copyright © EatAds, Inc. 2014 Block 71, Ayer Rajah Crescent #01-12 Singapore 139951</h5></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
            <!-- footer  -->

        </div>
        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            $(function() {
                //$('#modal-login').modal('show');
                //$('#modal-forgotpassword').modal('show');
                //

                var status = <?php echo $status; ?>;
                if (status != 200) {
                    $('#modal-login').modal('show');
                }

                var fpc = '<?php echo $forgotPwdCode; ?>';
                if (fpc) {
                    //console.clear();
                    //console.log('is');
                    //console.log(fpc);

                    // verify the hash
                    $.ajax({
                        url: "<?php echo Yii::app()->urlManager->createUrl('ajax/verifyresethash'); ?>",
                        data: {
                            hash: fpc
                        },
                        async: false,
                        success: function(data) {
                            //obj = JSON.parse(data);
                            //console.log('succ' + obj);
                            //console.log(data);
                            if (data == 1) {
                                $('#reset_warning').hide();
                            } else if (data == 2) {
                                $('#reset_modal_form').hide();
                                $('#reset_err_msg').html("Your reset password link is expired. Don't worry, get it again from <a href='#' id='reset_modal_forgot'>Forgot Password?</a>");
                            } else if (data == 3) {
                                $('#reset_modal_form').hide();
                                $('#reset_err_msg').html("This link is already used. You can reset your password from <a href='#' id='reset_modal_forgot'>Forgot Password?</a>");
                            } else if (data == 4) {
                                $('#reset_modal_form').hide();
                                $('#reset_err_msg').html('The link you used is not valid. Please check it is the correct link from the email.');
                            }
                            $('#modal-resetpassword').modal('show');
                        }
                    });

                }

                $('#reset_modal_forgot').on("click", function() {
                    $('#modal-resetpassword').modal('hide');
                    $('#modal-forgotpassword').modal('show');
                });

                $('#forgotpassword').click(function() {
                    $('#modal-login').modal('hide');
                    $('#modal-forgotpassword').modal('show');
                });

                $('#reset_submit').click(function() {
                    var pwd = $('#reset_password').val();
                    var cpwd = $('#reset_confirm_password').val();
                    if (pwd === cpwd) {
                        // ajax call
                        //console.log('password matches');
                        $.ajax({
                            type: "POST",
                            url: "<?php echo Yii::app()->urlManager->createUrl('ajax/resetpwd'); ?>",
                            data: {
                                password: pwd,
                                hash: '<?php echo $forgotPwdCode; ?>'
                            },
                            async: false,
                            success: function(data) {                                
                                if (data == 5) {
                                    // login and redirect from server
                                    $('#reset_modal_form').hide();
                                    $('#reset_err_msg').html("Please login to your account.");
                                } else if (data == 2) {
                                    $('#reset_modal_form').hide();
                                    $('#reset_err_msg').html("Your reset password link is expired. Don't worry, get it again from <a href='#' id='reset_modal_forgot'>Forgot Password?</a>");
                                } else if (data == 3) {
                                    $('#reset_modal_form').hide();
                                    $('#reset_err_msg').html("This link is already used. You can reset your password from <a href='#' id='reset_modal_forgot'>Forgot Password?</a>");
                                } else if (data == 4) {
                                    $('#reset_modal_form').hide();
                                    $('#reset_err_msg').html('The link you used is not valid. Please check it is the correct link from the email.');
                                } else {
                                    $('#modal-resetpassword').modal('hide');
                                    window.location = data;
                                }
                                $('#reset_warning').show();
                            }
                        });
                    } else {
                        $('#reset_err_msg').html("The two passwords do not match.");
                        $('#reset_warning').show();
                    }
                });

                $('#forgot_submit').click(function() {
                    var email = $('#forgot_email').val();
                    //console.log(email);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo Yii::app()->urlManager->createUrl('ajax/forgotpwd'); ?>",
                        data: {
                            email: email
                        },
                        async: false,
                        success: function(data) {
                            //console.log(data);

                            if (data == 1) {
                                $('#forgot_modal_alert').html('<b>Leap to your mailbox.</b> Reset password. And be back on Monitorly!');
                                $('#forgot_modal_alert').removeClass('alert-warning');
                                $('#forgot_modal_alert').addClass('alert-success');
                                $('#forgot_modal_form').hide();
                                // remove text buttons
                            } else if (data == 2) {
                                $('#forgot_modal_alert').html("Our server couldn't send a mail to you. Sorry, it's acting quirky. Try again in some time.");
                                $('#forgot_modal_alert').removeClass('alert-success');
                                $('#forgot_modal_alert').addClass('alert-warning');
                            } else if (data == 3) {
                                $('#forgot_modal_alert').html("We don't have this email in our system. Check if your entered it correctly.");
                                $('#forgot_modal_alert').removeClass('alert-success');
                                $('#forgot_modal_alert').addClass('alert-warning');
                            }
                            $('#forgot_modal_alert').show();
                        }
                    });
                });


                $('#home_login').click(function(e) {
                    //console.log('clicked');
                    e.preventDefault();
                    $('#LoginModal').modal('show');
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
      $("#mobile-number").intlTelInput({
        //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do']
        preferredCountries: ["in","sg", "us"],
        autoFormat: true,
        utilsScript: "<?php echo Yii::app()->request->baseUrl; ?>/js/libphonenumber/build/utils.js"
      });
      
      $('#MonitorlySubscription_email').blur(function(){
          var email = $('#MonitorlySubscription_email').val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        //if it's valid email
        if (filter.test(email)) {
            $('#MonitorlySubscription_email').attr('style', 'background-color:white');
        } else {
            $('#MonitorlySubscription_email').focus();
            $('#MonitorlySubscription_email').val('');
            $('#MonitorlySubscription_email').attr('style', 'background-color:rgb(223, 190, 190)');
        }    
      }) 
      
      $(function() {

        //autocomplete for company name in vendor subscription form
        var allVendorJson = JSON.parse('<?php echo $vendorList; ?>');
        //console.log(allVendorJson);
        $('.companyname').autocomplete({
            source: allVendorJson,
            select: function(event, ui) {
                console.log(ui.item.value + ', ' + ui.item.id);
                $("#vendor-ac-id").val(ui.item.id);
            },
            change: function(event, ui) {
                if (ui.item == null) {
                    $(".companyname").val('');
                    $("#vendor-ac-id").val('');
                    $(".companyname").focus();
                }
            },
            messages: {
                noResults: '',
                results: function() {
                }
            },
        })
    });
        </script>
    </body>
</html>