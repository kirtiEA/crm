<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/mdp.css" />
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.multidatespicker.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.min.js"></script>
<!-- Campaign creation Modal -->
<div class="modal fade" id="campaign_creation_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> -->
        <h1 class="modal-title text-center text-success headings-campaign" id="myModalfirstLabel">Start Creating Campaigns</h1>
        <h1 class="modal-title text-center hide text-success headings-campaign" id="myModalsecondLabel">Add Vendor Sites</h1>
      </div>
      <div class="modal-body row text-success" id="firstStep">
      	<div class="col-xs-12">
      		<div class="col-xs-6 wizard-date-select">
          <div><h3 class="headings-campaign center-block label label-success">Campaign Details</h3></div>
            <form action="wizard_submit" method="post" accept-charset="utf-8">
             <div class="form-group">
               <label for="Campaign_name">Name</label>
               <input type="name" class="form-control " name="Campaign[name]" id="campaignname" placeholder="Campaign Name" required>
             </div>
             <div class="form-group">
               <label for="startdate">Start Date</label>
               <input type="text" name="startdate" class="form-control datepicker" id="snewcampaigndate" placeholder="Campaign Start Date" required>
             </div>
             <div class="form-group">
               <label for="enddate">End Date</label>
               <input type="text" name="enddate" class="form-control datepicker" id="enewcampaigndate" placeholder="Campaign End Date" required>
             </div>
           </form>
         </div>
         <div class="col-xs-6">
           <div><h3 class="headings-campaign center-block label label-success">Schedule Campaign</h3></div>
           <div id="datepickerParent" class="col-xs-8"><div class="datepicker" id="altField"></div></div>
           <div class="col-xs-4" id="FiltersCampaignCreation">
               <p class="label label-success col-xs-12" id="FirstLastFilter" onclick="customDates(1);">First And Last</p>
             <p class="label label-default col-xs-12" id="AllDatesFilter" onclick="customDates(2);">All Days</p>
             <p class="label label-default col-xs-12" id="CustomDatesFilter" onclick="customDates(3);">Custom Dates</p>
           </div>
         </div>
       </div>
     </div>
     <div class="modal-body row hide" id="secondStep">
         <span id="createdcampaignid" class="hide"></span>  
       <div class="col-xs-12">
           <div  id="listings_campaign" style="overflow: scroll;"></div>
      </div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      <button type="button" class="btn btn-success" onclick="formValidation() ? createnewcampaign() : noValidation()" id="NextButtonCampaignModal">Next</button>
      <button type="button" class="btn btn-success hide" onclick="callMeSecondTime()" id="FinishButtonCampaignModal">Finish</button>
    </div>
  </div>
</div>
</div>
<script>
    var changedata = [];
  $(document).ready(function() {
    var today = new Date();
$('#altField').multiDatesPicker({
    altField: '#altField',
    minDate: today,
    dateFormat: 'dd M yy',
});
//    $('#listings_campaign').handsontable({
//       colHeaders: ['SITE CODE', 'NAME', 'CITY', 'LOCALITY', 'WIDTH', 'HEIGHT', 'MONITOR'],
//      //rowHeaders: false,
//      colWidths: [100, 150, 100, 150, 100, 100, 100, 100],
//      currentRowClassName: 'currentRow',
//      currentColClassName: 'currentCol',
//       manualColumnResize: true,
//       manualRowResize: true,
//      startRows: 20,
//      minSpareRows: 5,
//      onChange: function() {
//        if (arguments[0] !== null) {
//          changedata[changedata.length] = arguments[0][0][0];
//        } else {
//        }   
//      },
//      columns: [{
//        data: 'site_code',
//        type: 'text'
//      }, {
//        data: 'name',
//        type: 'text',
//      }, {
//        data: 'city',
//        type: 'text',
//      }, {
//        data: 'locality',
//        type: 'text',
//      }, {
//        data: 'width',
//        type: 'numeric'
//      }, {
//        data: 'length1',
//        type: 'numeric'
//      }, {
//        data: 'monitor',
//        type: 'dropdown',
//        source: 
//            }]
//          });
          
//function fetchSites() {
//  var handsontable = $('#listings').data('handsontable');
//  handsontable.loadData(null);
//  var parsedData = JSON.parse($('#data').html());
//  handsontable.loadData(parsedData);
//}


//function savecampaignnew() {
//  jQuery('#loading-image').show();        
//  var handsontableData = $('#listings').data('handsontable').getData();
//  var cleanData = cleanTableData(handsontableData);
//  var byuserid = '<?php //echo Yii::app()->user->id; ?>';
//  if (byuserid) {
//    if(cleanData.length) {
//      $.ajax({
//        type: 'POST',
//        url: '<?php //echo Yii::app()->urlManager->createUrl('ajax/massuploadsiteForCampaign'); ?>',
//        data: {
//          'byuserid': byuserid,
//          'data': JSON.stringify(cleanData)
//        },
//        success: function(data) {
//          jQuery('#loading-image').hide();
//          if (data == true)
//            location.reload();
//          else
//            alert('Failed to save data.')
//        },
//        error: function(data) {
//          jQuery('#loading-image').hide();
//          location.reload();
//        }
//      });
//    } else {
//
//    }
//  } else {
//    alert("Please select Media Vendor from the drop down");
//    $(window).scrollTop(0);
//    $('#vendor-ac').focus();
//  }
//}

});


//function cleanTableData(data) {
//  var cleanData = [];
//  var changedata1 = changedata.filter(onlyUnique);
//
//  data.forEach(function(row){
//    if(!row.id) {
//      cleanData.push({
//        site_code: row.site_code,
//        name: row.name,
//        mediatype: row.mediatype,
//        locality: row.locality,
//        city: row.city,
//        length: row.length1,
//        width: row.width,
//        lighting: row.lighting,
//        monitor: row.monitor,
//      });
//    }
//  });
//  console.log('cleanData : ' + cleanData.length);
//  for (var i = 0; i < changedata1.length; i++) {
//    var row = data[changedata1[i]];
//    if (row.name && row.mediatype && row.city && row.locality) {
//      cleanData.push({
//        id: row.id,
//        site_code: row.site_code,
//        name: row.name,
//        locality: row.locality,
//        city: row.city,
//        length: row.length1,
//        width: row.width
//      });
//    } 
//  }
//  console.log('cleanData2 : ' + cleanData.length);
//  return cleanData;
//}

//var callMeSecondTime = function () {
////    $('#campaign_creation_modal').modal("hide");
////    $('#flash-messages').show('slow', function() {
////    });
//    
//      jQuery('#loading-image').show();        
//  var handsontableData = $('#listings_campaign').data('handsontable').getData();
//  var cleanData = cleanTableData(handsontableData);
//  console.log(cleanData);
////  if (byuserid) {
//var cid = $('#createdcampaignid').html();
//    if(cleanData.length && cid.length) {
//      $.ajax({
//        type: 'POST',
//        url: $('#completePath').text() +'/ajax/massuploadsiteForCampaign',
//        data: {
//         // 'byuserid': byuserid,
//          'cid' : cid, 
//          'data': JSON.stringify(cleanData)
//        },
//        success: function(data) {
//          jQuery('#loading-image').hide();
//          if (data)
//            location.reload();
//          else
//            alert('Failed to save data.')
//        },
//        error: function(data) {
//          jQuery('#loading-image').hide();
//         // location.reload();
//        }
//      });
//    } else {
//        alert('Please enter sites data');
//    }
////  } else {
////    alert("Please select Media Vendor from the drop down");
////    $(window).scrollTop(0);
////    $('#vendor-ac').focus();
////  }
//};
</script>    

<!--<style>
    .ht_clone_top, .ht_clone_left, .ht_clone_corner {
  display: none; !important;
}
    </style>-->
