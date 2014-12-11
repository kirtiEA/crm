

$(document).ready(function () {

    setTimeout(function() {
        $("#flash-messages").hide('blind', {}, 500)
    }, 4000);

    //initializing multiselect dropdown
    $('.multiselect').multiselect({
        nonSelectedText: 'Select',
        enableFiltering: true
    });

    // start and end date behavior    
    $("#sdate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        minDate: 0,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            if (selectedDate)
                $("#edate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#edate").datepicker({
        minDate: 0,
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            $("#sdate").datepicker("option", "maxDate", selectedDate);
        }
    });
    // start date for campaign
    $("#scdate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        onClose: function (selectedDate) {
            if (selectedDate)
                $("#ecdate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#ecdate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd/mm/yy',
        onClose: function (selectedDate) {
            $("#scdate").datepicker("option", "maxDate", selectedDate);
        }
    });
    // dates for reports
    $("#srdate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        maxDate: 0,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            $("#erdate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#erdate").datepicker({
        maxDate: 0,
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            if (selectedDate)
                $("#srdate").datepicker("option", "maxDate", selectedDate);
        }
    });

    //collapse dropdown layers on clikcing on backdrop
    $(document).mouseup(function (e)
    {
        var container = $("#add-site-dropdown");

        if (!container.is(e.target)
                && container.has(e.target).length === 0)
        {
            $('#add-site-dropdown').removeClass('open');
        }
    });

    //dropdown remains open
    $(".multi-level-select").on("click", function (e) {
        if ($(this).hasClass("open")) {
            //$(e.currentTarget).toggleClass("open",false);
        } else {
            $(e.currentTarget).toggleClass("open", true);
            e.preventDefault();
            return false;
        }

    });

    $(".multi-level-select").on("hide.bs.dropdown", doNothing);
    $(".multi-level-select").on("show.bs.dropdown", doNothing);

    function doNothing() {
        //e.preventDefault(); 
        return false;
    }


    //modal
    /*$('#lightbox-btn').click(function() {
     $('div#img-gallery a:first-child').ekkoLightbox();
     });*/

    //invite vendor modal, close on clicking cancel
    $('#cancel').click(function (e) {
        console.log('text');
        $('#invite-vendor-modal').modal('hide');
    });

    //expand collapse content  
    $('.clickfor-show-hide').click(function (e) {

        $(this).siblings('.show-hide-content').each(function () {
            $(this).toggle();
        });

        //switch plus minus icons
        if ($(this).find('span').hasClass('glyphicon-plus')) {
            $(this).find('span').removeClass('glyphicon-plus').addClass('glyphicon-minus');
        }
        else {
            $(this).find('span').removeClass('glyphicon-minus').addClass('glyphicon-plus');
        }

    });

    //remove a site
//    $('.remove-icon').click(function(e) {
//        $(this).parent().remove();
//    });

    //change user password 
    $('.change-pwd').click(function () {
        $(this).hide();
        $(this).parent(".pull-right").append($('#hidden-change-pwd').html());
    });

    //save new password
    jQuery(document.body).on('click', '.save', function () {
        var saveBtn = $(this);
        var pwdTxt = $(this).siblings('.password');

        var id = $(this).parent().attr('id');
        var pwd = $(this).siblings('.password').val();
        //console.log(id);
        $.ajax({
            type: 'POST',
            url: $('#completePath').text() + '/ajax/UpdatePassword',
            data: {'id': id,
                'pwd': pwd},
            success: function (data) {

                //$(this).siblings('.password').remove();
                saveBtn.siblings('.change-pwd').show();
                saveBtn.siblings('.cancel').remove();
                saveBtn.remove();
                pwdTxt.remove();
                if (data == '200')
                    location.reload();

            }
        });
    });
    
    /* datepicker */
  $("#snewcampaigndate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        minDate: 0,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            if (selectedDate)
            {
                $("#edate").datepicker("option", "minDate", selectedDate);
                $('#altField').datepicker("option", "minDate", selectedDate);
//                $('#altField').datepicker("option", "addDates", selectedDate);
                $('#altField').multiDatesPicker({
                    addDates: [selectedDate]
                })
            }
        }
    });
    $("#enewcampaigndate").datepicker({
        minDate: 0,
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            $("#sdate").datepicker("option", "maxDate", selectedDate);
            $('#altField').datepicker("option", "maxDate", selectedDate);
//            $('#altField').datepicker("option", "addDates", selectedDate);
$('#altField').multiDatesPicker({
                    addDates: [selectedDate]
                })
        }
    });
// $("#snewcampaigndate").multiDatesPicker({
//     showAnim: "slide",
//     mode: "normal",
//     dateFormat: "dd M yy"
  // onClose: function( selectedDate ) {
  //   console.log(selectedDate);
  //  var month_val = selectedDate.slice(0, 2);
  //  var date_val = selectedDate.slice(3, 5);
  //  var year_val = selectedDate.slice(6, 10);
  //  console.log(month_val + date_val + year_val);
  //  if ($('#altField').find("td > a").val() == date_val) {
  //    $(this).addClass('ui-state-highight');
  //  }
  //  else { console.log("error baby error")};
  // }
  // });
});

    //cancel updating new password
    jQuery(document.body).on('click', '.cancel', function () {
        var cancelBtn = $(this);
        var pwdTxt = $(this).siblings('.password');
        cancelBtn.siblings('.change-pwd').show();
        cancelBtn.siblings('.save').hide();
        cancelBtn.remove();
        pwdTxt.remove();

    });

    //invite vendor by sending an email
    $('.invite').click(function () {
        //code to mail vendor goes here
        var email = $(this).parent().siblings('.modal-body').children('.email').val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        //if it's valid email
        if (filter.test(email)) {
            $.ajax({
                type: 'POST',
                url: $('#completePath').text() + '/ajax/InviteVendor',
                data: {'email': email,
                },
                success: function (data) {
                    console.log(data);
                    if (data == '200')
                        location.reload();
                }
            });
            $('#invite-vendor-modal').modal('hide');
        }
        else {
            $('.alert').toggle();
        }

    });


//flash messages fade in fade out
$('#flash-messages').fadeOut('slow', function () {
    $(this).remove();
})


function fetchNotifications() {
    //$('.dropdown-toggle').dropdown();
    $.ajax({
        type: 'POST',
        url: $('#completePath').text() + '/ajax/fetchNotifications',
        success: function (data) {
            console.log('sdf');
        },
        error: function (data) { // if error occured
            alert("Error occured.please try again");
            alert(data);
        }
    });

}


var createnewcampaign = function () {
   var name = $("#campaignname").val();
   var startdate = $("#snewcampaigndate").val();
   var enddate = $("#enewcampaigndate").val();
   var customdate = $("#altField").val();
  console.log("customdate is" + customdate + "name is" + name + "startdate is" + startdate + "enddate is" + enddate );

  $.ajax({
        type: 'POST',
        url: $('#completePath').text() + '/ajax/createNewCampaign',
        data : {
          'Campaign' : {
              'name' : name,
              'startDate' : startdate,
              'endDate' : enddate
          }  
        },
        success: function (data) {
           console.log('campaign id ' + data);
            $('#createdcampaignid').html(data);
            if (data) {
                console.log($('#completePath').text()+  '/myCampaigns/addsites?cid=' + data);
                window.location.href = $('#completePath').text()+  '/myCampaigns/addsites?cid=' + data;
//                $("#firstStep, #myModalfirstLabel,#NextButtonCampaignModal").addClass("hide");
//                $("#secondStep,#myModalsecondLabel,#FinishButtonCampaignModal").removeClass("hide");
            }
        },
        error: function (data) { // if error occured
            alert("Error occured.please try again");
            alert(data);
        }
    });
 };

    var formValidation = function () {
        console.log("form validation is being called");
      var name = $("#campaignname").val();
      var startdate = $("#snewcampaigndate").val();
      var enddate = $("#enewcampaigndate").val();
      console.log(name + startdate + enddate);
      if (name && startdate && enddate) {
        return true;
        // console.log("true that");
        // $("#NextButtonCampaignModal").removeClass("diabled");
      }else {
        console.log("false");
        return false;
      }
    };

    var noValidation = function () {
      console.log("no validation is being called");
      $(".form-group").addClass('has-error');
    };