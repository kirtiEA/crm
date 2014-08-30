<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/campaigns.js"></script>

<script type="text/javascript">
    $('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
    $(document).ready(function() {
        dust.render("campaigns", <?php echo $campaigns;?>, function(err, out) {
            $('#campaigns').html(out);
            //console.log(err);
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
        });
    });
    
</script>

<!-- Add Site Large Modal -->
    <div class="modal fade" id="add-site-modal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-extra-large">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h2 class="modal-title"><b>Add Sites</b> to Amul</h2>
          </div>
          <div class="modal-body">
              <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 left-content">
                        <div class="search-box-wrapper">
                            <input type="text" placeholder="Search Vendor">
                        </div>
                        <ul>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li class="selected">Vendor (2)</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                            <li>Vendor</li>
                        </ul>
                    </div>
                    <div class="col-md-9 right-content">
                        <div class="search-box-wrapper">
                            <input type="text" placeholder="Search Sites">
                        </div>
                        <ul>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li class="selected">Site<span class="glyphicon glyphicon-remove pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li class="selected">Site<span class="glyphicon glyphicon-remove pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                            <li>Site<span class="glyphicon glyphicon-plus pull-right"></span></li>
                        </ul>
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal">Cancel&nbsp;</a>
            <button type="button" class="btn btn-primary">Save</button>
          </div>
        </div>
      </div>
    </div>


<!-- add new user sub-header -->    

<div class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <!-- <form class="form-horizontol" role="form"> -->
             <?php $form=$this->beginWidget('CActiveForm', array(
                                'id'=>'create-campaign',
                                'action' => Yii::app()->getBaseUrl() .  '/campaign/create',   
                                //'enableClientValidation'=>true,
                                'clientOptions'=>array(
                                        'validateOnSubmit'=>true,
                                ),
                        )); ?>
                <div class="form-group">
                    <h3 class="subheader-heading">Add New Campaign</h3>
                    <div class="control">
                        <label class="control-label">Name</label>
                        <?php echo $form->textField($model,'name'); ?>
                        <?php echo $form->error($model,'name'); ?>
<!--                        
                        <input type="text">-->
                    </div>
                    <div class="control">
                        <label class="control-label">Start Date</label>
                        <?php echo $form->textField($model,'startDate', array('id' => 'sdate')); ?>
                        <?php echo $form->error($model,'startDate'); ?>
<!--                        <input type="text" class="datepicker" name="sdate" id="sdate" />-->
                    </div>
                    <div class="control">
                        <label class="control-label">End Date</label>
                        <?php echo $form->textField($model,'endDate', array('id' => 'edate')); ?>
                        <?php echo $form->error($model,'endDate'); ?>
<!--                        <input type="text" class="datepicker" name="edate" id="edate" />-->
                    </div>
                    <button class="btn btn-primary">Add</button>
                </div>
      <?php $this->endWidget(); ?>      
<!--            </form>-->
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
            <ul class="list" id="campaigns">
                
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