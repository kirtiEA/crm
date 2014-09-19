<div class="row contactus-content">
    <div class="col-md-12">
        <div class="contactus-content-headings">
            <h1>We'd love to hear from you.</h1>
            <h3>Easiest way to send us your queries, complaints or appreciations is to mail us at <span class="emphasis-text">support@eatads.com</span></h3>
        </div>
        <div class="contactus-form-wrap">
            <form class="form" role="form">
                <h2 class="form-heading"><img src="<?php $theme = Yii::app()->theme; echo $theme->getBaseUrl(); ?>/images/ic-contact.png"> Pen a Message</h2>
                <br>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Company">Company</label>
                    <input type="text" class="form-control">
                </div>
                <div class="form-group">
                    <label for="Comments">Comments</label>
                    <textarea class="form-control" rows="5"></textarea>
                </div>
                <button class="btn btn-primary">Send Your Message</button>
            </form>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="office-address">
                    <h2 class="office-address-heading">India Office</h2>
                    <h4 class="office-address-details"><span>177, Adharshila, 
                            First Floor, Gulmohar Park Road, Gautam Nagar, Delhi – 110049</span></h4>
                    <div class="office-address-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3504.4087185070293!2d77.21031113359074!3d28.55748708356945!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce26c73225c13%3A0xeb05f8052dd15af8!2sEatAds!5e0!3m2!1sen!2sin!4v1410872084036" width="400" height="300" frameborder="0" style="border:0"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="office-address">
                    <h2 class="office-address-heading">Singapore Office</h2>
                    <h4 class="office-address-details"><span>Block 71, Ayer Rajah Crescent #01-12 Singapore 139951</span></h4>
                    <div class="office-address-map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3988.796881288266!2d103.78727858042602!3d1.2965120738684281!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da1a4fd31e67b5%3A0x7257929d0b2aac77!2s71+Ayer+Rajah+Crescent%2C+Singapore+139951!5e0!3m2!1sen!2sin!4v1410874328460" width="400" height="300" frameborder="0" style="border:0"></iframe>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-number">
            <h3>Or ditch the fuss. Talk to a human.</h3>
            <br>
            <h2><span class="glyphicon glyphicon-phone-alt"></span> +91 11 4132 0334</h2>
        </div>
    </div>
</div>
<script>
    $(function() {        
        $('li.phone1').css({
            "margin-top": "15px",
            "font-size": "16px",
            "font-weight": "600",
            "margin-right": "10px"
        });
        $('#header_nav').removeClass('navbar-dark');
    });
</script>