
        
$(document).ready(function() {
    setTimeout(function() {
        $("#flash-messages").hide('blind', {}, 500)
    }, 4000);
    
    //click on Add Vendors Sites button
    
    $('.js-signup-btn').click(function(e) {
        window.location.href = 'signup.html';
    });
    
    $('.js-contactus-btn').click(function(e) {
        window.location.href = 'contactus.html';
    });
    
    $('.js-signup-btn-scrolltop').click(function() {
        $('html, body').animate({scrollTop:0}, 'slow');
    });
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
      });
});
