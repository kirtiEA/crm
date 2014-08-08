<script type="text/javascript">
    $('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
</script>
<div class="row controls clear">
    <div><button class="btn btn-success" id="add">Add new campaign </button></div>
    <div class="add_campaign">
        <input type="text" placeholder="Name of the Campaign" id="newCampName" class="form-control"/>
        <input type="text" data-date-format="dd/mm/yyyy" placeholder="Start Date" id="newCampStartDate" class="form-control date"/>
        <input type="text" data-date-format="dd/mm/yyyy" placeholder="End Date" id="newCampEndDate" class="form-control date"/>
        <button class="box btn-success" id="newCampSubmit">Add</button>
        <a href="#">Cancel</a>
    </div>
</div>
<div class="row addons" id="campaignsList">
    <select class="order" id="orderList">
        <option value="#">Newest first</option>
    </select>

</div>
<script type="text/javascript">
    $(function() {
        var FromEndDate, startDate, ToEndDate;
        var today = new Date();
        //$( "#newCampStartDate" ).datepicker( "option", "dateFormat", 'yy/mm/dd');
        //$( "#newCampEndDate" ).datepicker( "option", "dateFormat", 'yy/mm/dd');
        $('#newCampStartDate').datepicker({
            dateFormat: 'yy-mm-dd',
            weekStart: 1,
            minDate: 0,
            startDate: today,
            endDate: FromEndDate,
            autoclose: true
        })
        .on('changeDate', function(selected) {
            startDate = new Date(selected.date.valueOf());
            startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
            $('#newCampEndDate').datepicker('setStartDate', startDate);
        });
        $('#newCampEndDate').datepicker({
            dateFormat: 'yy-mm-dd',
            weekStart: 1,
            startDate: startDate,
            endDate: ToEndDate,
            autoclose: true
        })
        .on('changeDate', function(selected) {
            FromEndDate = new Date(selected.date.valueOf());
            FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
            $('#newCampStartDate').datepicker('setEndDate', FromEndDate);
        });
        
        $('#newCampSubmit').click(function() {
            console.clear();
            var campaignData = {
                campName: $('#newCampName').val(),
                campStart: $('#newCampStartDate').val(),
                campEnd: $('#newCampEndDate').val()
            };
            Monitorly.addCampaign(campaignData, newCampaignLoad);
        });
        $('.controller').live("click", function(e) {
            var parentContainer = $(this).parent().parent().parent();
            if (parentContainer.hasClass('open')) {
                // already open
                parentContainer.removeClass('open');
            } else {
                // open details
                parentContainer.addClass('open');
                var campaignId = $(this).closest('div.item').attr('id');
                $('div#' + campaignId).find('div.detailed').html('');
                Monitorly.getCampaignSites(campaignId, updateSitesView);
            }
        });

        $('#authSignIn').on("click", function() {
            var username = $("#username").val();
            var password = $("#password").val();        
        });
        

    });
</script>