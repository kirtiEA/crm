function showCampaign() {
    $("a.controller").on("click", function() {
        var parentContainer = $(this).parent().parent();
        if (parentContainer.hasClass('open')) {
            // already open
            parentContainer.removeClass('open');
        } else {
            // open details
            parentContainer.addClass('open');
            var campaignId = parentContainer.attr('id');
            console.log('record id - ' + campaignId);
            fetchCampaignDetails(campaignId);
        }
    });

}
function showZones() {
    $("li.zones").on("click", function(e) {
        e.stopImmediatePropagation();
        // only for li zones click        
        if ($(e.target).hasClass('zones')) {
            if ($(this).hasClass('opened')) {
                $(this).children('ul.sub').hide();
                $(this).removeClass('opened');
            } else {
                $(this).children('ul.sub').show();
                $(this).addClass('opened');
            }
        }
    });
    $('.assignee a').on("click", function(e) {
        console.log('assignee click');
        if ($(this).children('.popover').hasClass('show')) {            
            console.log('pop over hide');
            $(this).children('.popover').hide();
            $(this).children('.popover').removeClass('show');
        } else {
            console.log('pop over show');
            $(this).children('.popover').show();
            $(this).children('.popover').addClass('show');
        }
        e.preventDefault();
    });
    /*$('.assignee a').on("click", function(e) {
     
     $(this).popover({
     html: true,
     content: function() {
     return $('#popoverContent').html();
     },
     //title: function() {
     //return $('#popoverExampleTwoHiddenTitle').html();
     //}
     });
     e.preventDefault();
     });*/
}