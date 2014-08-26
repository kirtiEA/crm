<script type="text/javascript">
    $('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
</script>

<!-- add new user sub-header -->    

<div class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontol" role="form">
                <div class="form-group">
                    <h3 class="subheader-heading">Add New Campaign</h3>
                    <div class="control">
                        <label class="control-label">Name</label>
                        <input type="text">
                    </div>
                    <div class="control">
                        <label class="control-label">Start Date</label>
                        <input type="text" class="datepicker" name="sdate" id="sdate" />
                    </div>
                    <div class="control">
                        <label class="control-label">End Date</label>
                        <input type="text" class="datepicker" name="edate" id="edate" />
                    </div>
                    <button class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of add new user sub-header --> 

<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab">Created by Me</a></li>
    <li><a href="#profile" role="tab" data-toggle="tab">Assigned to Me</a></li>
</ul>

<!-- campaigns list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <button type="button" class="btn btn-default active">Active (2)</button>
                <button type="button" class="btn btn-default">Upcoming (2)</button>
                <button type="button" class="btn btn-default">Expired (1)</button>
            </div>
            <h1 class="list-heading">Campaign List (2)</h1>
            <ul class="list">
                <li class="list-item">
                    <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;Amul (12)</h2>
                    <h3>&nbsp;&nbsp;02/08/2014 - 28/08/2014</h3>
                    <div class="pull-right">
                        <button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                        &nbsp;
                        <button class="btn btn-primary"> Save</button>
                    </div>

                    <div class="list-item-content show-hide-content">
                        <div class="list-action">
                            <h3>Add Sites</h3>&nbsp;
                            <div class="dropdown multi-level-select" id="add-site-dropdown">
                                <button id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" data-target="#" href="/page.html">
                                    Select Sites <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#">Selvel</a>
                                        <ul class="dropdown-menu">
                                            <li><input type="checkbox"> Site 1</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                        </ul>
                                    </li>
                                    <li class="dropdown-submenu">
                                        <a tabindex="-1" href="#">Graphisads</a>
                                        <ul class="dropdown-menu">
                                            <li class="selected"><input type="checkbox"> Site 1</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                            <li><input type="checkbox"> Site 2</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                            <button class="btn btn-secondary">Add</button>
                        </div>
                        <ul class="sub-list">
                            <li>
                                <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;Selvel (3) &nbsp;
                                </h3>
                                <div class="assign-dropdown">Assigned to
                                    <select>
                                        <option>Selvel</option>
                                        <option>Myself</option>
                                    </select>
                                </div>
                                <ul class="sub-sub-list show-hide-content">
                                    <li>Green Park &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                    <li>Safdurjang &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                    <li>Hauz Khas &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                </ul>
                            </li>
                            <li>
                                <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;Roshan (2)
                                </h3>
                                <div class="assign-dropdown">Assigned to
                                    <select>
                                        <option>Roshan</option>
                                        <option>Myself</option>
                                    </select>
                                </div>
                                <ul class="sub-sub-list show-hide-content">
                                    <li>Bandra West &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                    <li>Andheri East &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="list-item">
                    <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;Volkswagen (8)</h2>
                    <h3>&nbsp;&nbsp;02/08/2014 - 28/08/2014</h3>
                    <div class="pull-right">
                        <button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                        &nbsp;
                        <button class="btn btn-primary"> Save</button>
                    </div>

                    <div class="list-item-content show-hide-content">
                        <div class="list-action">
                            Add Sites&nbsp;
                            <select class="multiselect" multiple="multiple">
                                <optgroup label="Roshan (6)">
                                    <option>Churney Road</option>
                                    <option>Hughes Road Chowpatty</option>
                                    <option>Dadar Shivaji Park</option>
                                    <option>CP Tank Junction Nr Kotha</option>
                                    <option>Andheri Lokhandwala Opp</option>
                                    <option>Sion Flyover Nr Sion Ho</option>
                                </optgroup>
                                <optgroup label="Graphisads (3)">
                                    <option>Green Park</option>
                                    <option>Laxmi Nagar</option>
                                    <option>Lajpat Nagar</option>
                                </optgroup>
                            </select>
                            <button class="btn btn-secondary">Add</button>
                        </div>
                        <ul class="sub-list">
                            <li>
                                <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;Pioneer (3) &nbsp;
                                </h3>
                                <div class="assign-dropdown">Assigned to
                                    <select>
                                        <option>Pioneer</option>
                                        <option>Myself</option>
                                    </select>
                                </div>
                                <ul class="sub-sub-list show-hide-content">
                                    <li>Green Park &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                    <li>Safdurjang &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                    <li>Hauz Khas &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                </ul>
                            </li>
                            <li>
                                <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;Bright Outdoors (2)
                                </h3>
                                <div class="assign-dropdown">Assigned to
                                    <select>
                                        <option>Bright Outdoors</option>
                                        <option>Myself</option>
                                    </select>
                                </div>
                                <ul class="sub-sub-list show-hide-content">
                                    <li>Bandra West &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                    <li>Andheri East &nbsp;<span class="glyphicon glyphicon-remove remove-icon"></span></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- end of campaigns list --> 


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
        $('.controller').on("click", function(e) {
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