<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/lightbox_dust.js"></script>
<script type="text/javascript">
$(document).ready( function () {
/* code inside doc.ready starts*/
window.onscroll = scroll;
/* scroll function for fixing css properties using javascript */
function scroll () {
if($(window).scrollTop() >= origOffsetY) {
	/* first if statement starts */
	$('#submenu').css("margin-top","0px");
	$('#submenu').addClass('navbar-fixed-top');
	/* Second if else statement starts */
	if($(window).scrollTop() >= tbOffsetY) {
	 $('.standstill').css("position","fixed");
	}else{
	 $('#submenu').css("margin-top","-20px");
	 $('#submenu').removeClass('navbar-fixed-top');
	}
	/* Second if else statement ends */
}
	/* first if statement ends */
        
         if ($(window).scrollTop() == ($(document).height() - $(window).height())) {
        fetchNextTasks(1);
    }
}
/* Legacy code starts*/
$('.standstill').css("table-layout","fixed");
<?php if ($vendorName) :?>

var name = <?php echo '"'. $vendorName .'"';?>;
$('#vendorName').html(name);

<?php endif;?>
var menu = $('#submenu');
var origOffsetY = menu.offset().top;

var tbheader = $('.standstill');
if (tbheader && tbheader.offset()) {
	var tbOffsetY = tbheader.offset().top;    
}
$('.mon_menu').each(function() {
	$(this).removeClass('active');
});
$('.menu_report').addClass('active');

/* Legacy code ends*/

	/* code inside doc.ready ends*/
});

/* Global Variable declaration */
var start = 20, limit = 10;

/* filter function starts */
var filter = function () {

  fetchNextTasks(2);
};

/* filter function ends */

/*fetchnexttasks starts */
 var fetchNextTasks = function (id) {
 	console.log("Hey there I have been called on click");
 	if (id == 2) {start = 0;}
 	$.ajax({
 		type: 'POST',
 		url: '<?php echo Yii::app()->urlManager->createUrl('/ajax/filterAllReports'); ?>',
 		data: {
 			'campaignids': JSON.stringify($('#multiselect-campaigns').val()),
 			'userids' : JSON.stringify($('#multiselect-users').val()),
 			'sdate' : $('#srdate').val(),
 			'edate' :$('#erdate').val(),
 			'start' :start
 		},
 		success: function (data) {
 			start += limit;
 			console.log("start is" + start);
 			var template = $('#task_row').html();
		  Mustache.parse(template);   // optional, speeds up future uses
		  var rendered = Mustache.render(template, JSON.parse(data));
		  if (id == 1) {
		  	$('#tbody_reports').append(rendered);
		  } else if (id == 2) {
		  	$('#tbody_reports').html(rendered);
		  }     
		  $('#task_cnt').html(start);
		},
		error: function(data) { // if error occured
			alert("Error occured.please try again");
			alert(data);
		}
		})
};
/* fetchnexttasks ends */

/* share campaign zip to emails starts */
var shareCampaignZipToEmails = function () {
var id = $('#selectedShareCampaign_zip').val();
var emails = $('#share_emails_zip').val();
if(id && emails) {
$.ajax({
type: 'POST',
url: $('#completePath').text() + '/ajax/shareCampaignZipImages',
data: {
	'id': id,
	'emails' : emails
},
success:function(data){
	console.log(data.length + ' asdad ');
	var json = data;
	if (data.trim().length > 0) {
		$('.alert').text('The email ' + data + ' is invalid');
		$('.alert').show();
	} else {
		location.reload();
	}
},
error: function(data) { // if error occured
	alert("Error occured.please try again");
	alert(data);
}
});
/* share campaign zip to emails ends */
  }
}

</script>

     <!-- share zip modal -->
     <div class="modal fade" id="share-zip-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
     	<div class="modal-dialog modal-sm modal-sm-custom">
     		<div class="modal-content">
     			<div class="modal-header">
     				<h3><b>Download Campaign Images</b></h3>
     			</div>
     			<div class="modal-body">
     				<label>Select Campaign</label>&nbsp;
     				<select name="campaign" id="selectedShareCampaign_zip">
     					<?php foreach($campaignIdList as $key => $value) {
     						echo "<option value='$key'>$value</option>";                            
     					} ?>
     				</select>
     				<br/>
     				<!--                    <label>Email</label>&nbsp;-->

     				<textarea placeholder="Enter comma separated Emails" style="width: 215px;" id="share_emails_zip"></textarea>
     			</div>
     			<div class="modal-footer">
     				<div class="alert alert-danger" role="alert" style="display:none;">Please enter correct email id</div>
     				<a href="#" id="cancel" data-dismiss="modal">Cancel</a>&nbsp;
     				<button class="btn btn-primary" id="sharezip" onclick="shareCampaignZipToEmails();">Download Images</button>
     			</div>
     		</div>

     	</div>
     </div>
     <!-- end of invite vendor modal -->


     <div class="modal fade" id="download-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
     	<div class="modal-dialog modal-sm modal-sm-custom">
     		<form class="form-horizontol" role="form" method="post" id="report-form" action="downloadreport">
     			<div class="modal-content">
     				<div class="modal-header">
     					<h3><b>Download Report</b></h3>
     				</div>
     				<div class="modal-body">


     					<label>Select Campaign</label>&nbsp;
     					<select name="campaign">
     						<?php foreach($campaignIdList as $key => $value) {
     							echo "<option value='$key'>$value</option>";                            
     						} ?>
     					</select>


     				</div>
     				<div class="modal-footer">
     					<a href="#" id="cancel" data-dismiss="modal">Cancel</a>&nbsp;
     					<button class="invite btn btn-primary" id="report_submit" data-dismiss="modal">Download Report</button>
     				</div>
     			</div>
     		</form>

     	</div>
     </div>

     <!-- filters sub-header -->    

     <div id="submenu" class="container-fluid sub-header">
     	<div class="row">
     		<div class="col-md-12">
     			<form class="form-horizontol" role="form" method="post" id="filter-form" >
     				<div class="form-group">
     					<h3 class="subheader-heading">Filter</h3>
     					<?php if (!Yii::app()->user->isGuest) :?>
     						<div class="control">
     							<label class="control-label">Campaigns</label>
     							<select class="multiselect" id="multiselect-campaigns" multiple="multiple">
     								<?php foreach($campaignIdList as $key => $value) {
     									echo "<option value='$key'>$value</option>";                            
     								} ?>
     							</select>
     						</div>
     					<?php endif;?>
     					<div class="control">
     						<label class="control-label">Assigned To</label>
     						<select class="multiselect" id="multiselect-users" multiple="multiple">
     							<?php foreach($assignedToList as $key => $value) {
     								echo "<option value='$key'>$value</option>";                            
     							} ?>
     						</select>
     					</div>
     					<div class="control">
     						<label class="control-label">Start Date</label>
     						<input type="text" class="datepicker" name="sdate" id="srdate" />
     					</div>
     					<div class="control">
     						<label class="control-label">End Date</label>
     						<input type="text" class="datepicker" name="edate" id="erdate" />
     					</div>
     					<input type="hidden" name="campaignids" id="campaignids" />
     					<input type="hidden" name="assignedto" id="assignedto" />

     					<div name="filter_submit" id="filter_submit" class="btn btn-primary" onclick="filter();">Filter</div>

     				</div>
     			</form>
     		</div>
     	</div>

     </div>
     <!-- end of filters sub-header --> 



     <ul class="nav nav-tabs" role="tablist">
     	<li class="active"><a href="#">Full Campaign</a></li>
     	<?php if(!Yii::app()->user->isGuest) : ?>
     		<li><a href="<?php echo Yii::app()->urlManager->createUrl('reports'); ?>">Proof of Posting</a></li>
     	<?php endif;?>
     </ul>

     <!-- tasks list --> 
     <div class="container-fluid content-wrapper">
     	<div class="row">
     		<div class="col-md-12">
     			<h1 class="list-heading pull-left">Report</h1>
     			<button class="btn btn-secondary table-control pull-right" data-toggle="modal" data-target="#download-modal"><span class="glyphicon glyphicon-download"></span> Download Report</button>
     			<button class="btn btn-secondary table-control pull-right" data-toggle="modal" data-target="#share-zip-modal"><span class="glyphicon glyphicon-download"></span> Download Campaign Images</button>

     			<table class="table table-condensed" style="table-layout:fixed">
     				<thead>
     					<tr>
     						<th>Campaign</th>
     						<th>Site</th>
     						<th>Location</th>
     						<th>Media Type</th>
     						<th>Assigned To</th>
     						<th>Due Date</th>
     						<th>Status</th>
     						<th>Photo</th>
     					</tr>
     				</thead>
     			</table>

     			<div id="rcontent" class="div-table-content">

     				<table class="table table-hover" style="table-layout:fixed">
     					<tbody id="tbody_reports" class="scroll">

     						<?php
     						foreach ($tasks as $t):
     							$trClass = '';
     						if ($t['status'] == 0) {
     							$trClass = 'danger';
     						}
     						?>
     						<tr class='<?php echo $trClass; ?>' id="<?php echo $t['id']; ?>">
     							<td><?php echo $t['campaign']; ?></td>
     							<td><?php echo $t['site']; ?></td>
     							<td><?php echo $t['location']; ?></td>
     							<td><?php echo $t['mediatype']; ?></td>
     							<td><?php echo strlen($t['assignedto']) ? $t['assignedto'] : 'Unassigned'; ?></td>
     							<td><?php echo date('d/m/Y', strtotime($t['duedate'])); ?>
     								<input type="hidden" class="duedate" value="<?php echo date('Y-m-d', strtotime($t['duedate'])); ?>" />
     								<input type="hidden" class="pop" value="<?php echo $t['pop']; ?>" />
     							</td>
     							<td>
     								<?php
     								$status = '';
     								if ($t['status'] == 0) {
     									if($t['duedate'] < date('Y-m-d'))
     										$status = 'Missed';
     									else 
     										$status = 'Pending';
     								} else {
     									if ($t['problem']) {
     										$status = '<img src="' . Yii::app()->request->baseUrl . '/images/warning.png">';
     									} else {
     										$status = '<img src="' . Yii::app()->request->baseUrl . '/images/ok.png">';
     									}
     								}
     								echo $status;
     								?>
     							</td>
     							<td>
     								<?php
     								if ($t['status'] == 0) {
     									echo '-';
     								} else {
     									echo '<a href="javascript:void(0);" class="lightbox-btn" onclick="lightBoxView(' . $t['id'] .');">View ('.$t['photocount'].')</a>';
     								}
     								?>
     							</td>
     						</tr>

     					<?php endforeach; ?>
     				</tbody>

     			</table>
     			<div class="next jscroll-next-parent" ><a onclick="fetchNextTasks(1);">next</a></div>
     		</div>

     	</div>
     	<div id="img-gallery" style="display:block;"></div>
     </div>
   </div>
   <!-- end of tasks list --> 


   <script id="task_row">
   {{#.}}
   	<tr class='{{class}}' id="{{id}}" >
   		<td>{{campaign}}</td>
   		<td>{{site}}</td>
   		<td>{{location}}</td>
   		<td>{{mediatype}}</td>
   		<td>{{#assignedto}} {{assignedto}} {{/assignedto}} {{^assignedto}}Unassigned{{/assignedto}}</td>
   		<td>{{duedateNew}}</td>
   		<td>{{#class}}{{problemstatus}}{{/class}} {{^class}}<img src="{{problemImage}}"> {{/class}}</td>
   		<td> {{#class}}-{{/class}} {{^class}}<a href="javascript:void(0);" class="lightbox-btn" onclick="lightBoxView({{id}});">View ({{photocount}})</a>{{/class}}</td>
   	</tr>
   	{{/.}}
   </script>
   
   
   <script>
               /* Bind scroll to table id rcontent */
 $('#rcontent').bind('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
            console.log('asd');
            fetchNextTasks(1);
        }
    });
    /* lightbox start */
function lightBoxView (id) {
	$.ajax({
		url: "<?php echo Yii::app()->urlManager->createUrl('ajax/fetchppimages'); ?>",
		type: 'POST',
		data: {
			taskid: id
		},
		async: false,
		success: function (data) {
			dust.render("lightbox", JSON.parse(data), function (err, out) {
				$("#img-gallery").html(out);
				//console.log(err);
			})
		$('div#img-gallery a:first-child').ekkoLightbox();
		}
	})
}
/* lightbox ends */            

$('#report_submit').click(function(e){
	e.preventDefault();        
	$('#report-form').submit();
});
</script>               