<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/mdp.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.multidatespicker.js"></script>

 <!-- Campaign creation Modal -->
<div class="modal fade" id="campaign_creation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title text-center" id="myModalfirstLabel">Start Creating Campaigns</h4>
        <h4 class="modal-title text-center hide" id="myModalsecondLabel">Add Vendor Sites</h4>
    </div>
      <div class="modal-body row" id="firstStep">
      	<div class="col-xs-12">
      		<div class="col-xs-6 wizard-date-select">
      		<h4>Campaign Details</h4>
      			<form action="wizard_submit" method="post" accept-charset="utf-8">
      			    <div class="form-group">
							    <label for="nameofcampaign">Name</label>
							    <input type="name" class="form-control" id="nameofcampaign" placeholder="Name">
							  </div>
							  <div class="form-group">
							    <label for="startdate">Start Date</label>
							    <input type="text" class="form-control datepicker" id="startdate" placeholder="Start Date">
							  </div>
							  <div class="form-group">
							    <label for="startdate">End Date</label>
							    <input type="text" class="form-control datepicker" id="enddate" placeholder="End date">
							  </div>
						</form>
      		</div>
      		<div class="col-xs-6">
      			<h4>Schedule Campaigns</h4>
      			<div class="datepicker" id="altField"></div>
      		</div>
      	</div>
      </div>
      <div class="modal-body row hide" id="secondStep">
      	<div class="col-xs-12">
      		
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="callMe()">Next</button>
      </div>
    </div>
  </div>
</div>