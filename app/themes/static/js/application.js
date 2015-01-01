
        
$(document).ready(function() {

  if($('.loginForm')){
    $('.loginForm').parent().css({
    'background-image': 'url(themes/static/images/bg.jpg)',
    'background-size': 'cover',
    'margin-top': '0em',
    'overflow': 'none',
    'height':'100vh'
  });
  }
    setTimeout(function() {
        $("#flash-messages").hide('blind', {}, 500)
    }, 4000);
    
    //click on Add Vendors Sites button
    
    $('.js-signup-btn').click(function(e) {
        window.location.href =  $('#completePath').text() + '/account/signup';
    });
    
    $('.js-contactus-btn').click(function(e) {
        window.location.href = $('#completePath').text() + '/account/contactus';
    });
    
    $('.js-signup-btn-scrolltop').click(function() {
        $('html, body').animate({scrollTop:0}, 'slow');
    });
    $("#mobile-number").intlTelInput({
        //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do']
        preferredCountries: ["in","sg", "us"],
        autoFormat: true,
        utilsScript: $('#completePath').text() + "/js/libphonenumber/build/utils.js"
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
	  
	  //anchor links on homepage
	  $('#selling-point-phone-anchor').click(function(e) {
		      $('html, body').animate({
		          scrollTop: $('#selling-point-phone').offset().top
		      }, 1200);
	  });
	  
	  $('#selling-point-sync-anchor').click(function(e) {
		      $('html, body').animate({
		          scrollTop: $('#selling-point-sync').offset().top
		      }, 1200);
	  });
	  
	  $('#selling-point-certification-anchor').click(function(e) {
		      $('html, body').animate({
		          scrollTop: $('#selling-point-certification').offset().top
		      }, 1200);
	  });
	  
	  $('#selling-point-report-anchor').click(function(e) {
		      $('html, body').animate({
		          scrollTop: $('#selling-point-report').offset().top
		      }, 1200);
	  });
	  
//	  if($('#selling-points-subnav').scrollTop = 300) {alert('hi');}
	  //if ($('#selling-points-subnav').offset() = 0)
	  //{alert('navbar should be sticky now');}
    
          /* Adding a class dirty for in-built validation */
           $('#emailid').add("#mobile-number").add("#email").focusout( function() {
                  $(this).addClass("dirty");
              });
           /* validation for email only */
           $('#emailid').focusout( function () {
            var val = $(this).val();
            var email = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i; 
            if(email.test(val)){
              $(this).css({'border-color': '#3c763d',});
            } else { 
              $(this).css({'border-color': '#a94442',});
            }
           });
          
          
});
