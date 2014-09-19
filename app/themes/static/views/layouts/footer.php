<!-- contact details -->
<div class="row contact-details-wrap">
    <div class="col-md-12">
        <div class="contact-details">
            <h2>Even More Curious, Approach Us Anytime.</h2>
            <div class="contact-info pull-left">
                <span class="glyphicon glyphicon-phone-alt"></span>  +91 11 4132 0334 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="mailto:sales@eatads.com"><span class="glyphicon glyphicon-envelope"><span class="contact-info-email">sales@eatads.com</span></a></span>  
            </div>
            <div class="social-icons pull-right">                
                <a href="https://www.linkedin.com/company/2128549" target="_blank"><img src="<?php echo $theme->getBaseUrl(); ?>/images/linkedin.png">&nbsp;</a>
                <a href="https://www.facebook.com/pages/EatAds/222452511283884" target="_blank"><img src="<?php echo $theme->getBaseUrl(); ?>/images/facebook.png"></a>
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
                <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/pricing'); ?>">Pricing</a></li>
                <li><a href="terms.html">Terms &amp; Conditions</a></li>
                <li><a href="<?php echo Yii::app()->urlManager->createUrl('account/contactus'); ?>">Contact Us</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><h5 class="pull-right">Copyright © EatAds, Inc. 2014 Block 71, Ayer Rajah Crescent #01-12 Singapore 139951</h5></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
<!-- footer  -->

</body>
</html>
