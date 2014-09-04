

$(document).ready(function() {

    //initializing multiselect dropdown
    $('.multiselect').multiselect({
        nonSelectedText: 'Select',
        enableFiltering: true
    });

    //start and end date behavior
    $("#sdate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        minDate: 0,
        dateFormat: 'dd M yy',
        onClose: function(selectedDate) {
            if (selectedDate)
                $("#edate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#edate").datepicker({
        minDate: 0,
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd M yy',
        onClose: function(selectedDate) {
            $("#sdate").datepicker("option", "maxDate", selectedDate);
        }
    });

    //collapse dropdown layers on clikcing on backdrop
    $(document).mouseup(function(e)
    {
        var container = $("#add-site-dropdown");

        if (!container.is(e.target)
                && container.has(e.target).length === 0)
        {
            $('#add-site-dropdown').removeClass('open');
        }
    });

    //dropdown remains open
    $(".multi-level-select").on("click", function(e) {
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
    $('#lightbox-btn').click(function() {
        $('div#img-gallery a:first-child').ekkoLightbox();
    });

    //invite vendor modal, close on clicking cancel
    $('#cancel').click(function(e) {
        console.log('text');
        $('#invite-vendor-modal').modal('hide');
    });

    //expand collapse content  
    $('.clickfor-show-hide').click(function(e) {

        $(this).siblings('.show-hide-content').each(function() {
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
    $('.remove-icon').click(function(e) {
        $(this).parent().remove();
    });

    //change user password 
    $('.change-pwd').click(function() {
        $(this).hide();
        $(this).parent(".pull-right").append($('#hidden-change-pwd').html());
    });

    //save new password
    jQuery(document.body).on('click', '.save', function() {
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
            success: function(data) {
                alert("Password updated successfully");
                //$(this).siblings('.password').remove();
                saveBtn.siblings('.change-pwd').show();
                saveBtn.siblings('.cancel').remove();
                saveBtn.remove();
                pwdTxt.remove();

            }
        });
    });

    //cancel updating new password
    jQuery(document.body).on('click', '.cancel', function() {
        var cancelBtn = $(this);
        var pwdTxt = $(this).siblings('.password');
        cancelBtn.siblings('.change-pwd').show();
        cancelBtn.siblings('.save').hide();
        cancelBtn.remove();
        pwdTxt.remove();

    });

    //invite vendor by sending an email
    $('.invite').click(function() {
        //code to mail vendor goes here
        var email = $(this).parent().siblings('.modal-body').children('.email').val();
        var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
        //if it's valid email
        if (filter.test(email)) {
            $.ajax({
                type: 'POST',
                url: $('#completePath').text() + '/ajax/invitevendor',
                data: {'email': email,
                },
                success: function(data) {
                    alert("Vendor invited successfully ");
                }
            });
        }
        else {
            alert('Please enter correct email address in the format abc@xyz.pq');
        }
        $('#invite-vendor-modal').modal('hide');

    });

});
