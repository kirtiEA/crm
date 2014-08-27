

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
        onClose: function(selectedDate) {
            if (selectedDate)
                $("#edate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#edate").datepicker({
        minDate: 0,
        //changeMonth: true,
        numberOfMonths: 1,
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



});
