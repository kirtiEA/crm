
        
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
});
