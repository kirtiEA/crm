<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/handsontable.full.css" />
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/handsontable.full.js"></script>
<div class="col-xs-12">
    <div  id="listings_campaign" ></div>
</div>
<span class="hide" id="sidedata"><?php echo json_encode($listings);?></span>
<script>
    var changedata = [];
  $(document).ready(function() {
    
    $('#listings_campaign').handsontable({
      colHeaders: ['SITE CODE', 'NAME', 'CITY', 'LOCALITY', 'WIDTH', 'HEIGHT', 'MONITOR'],
      rowHeaders: true,
      colWidths: [150, 250, 200, 250, 100, 100, 100, 200],
      currentRowClassName: 'currentRow',
      currentColClassName: 'currentCol',
       manualColumnResize: true,
       manualRowResize: true,
      minSpareRows: 20,
      onChange: function() {
        if (arguments[0] !== null) {
          changedata[changedata.length] = arguments[0][0][0];
        } else {
        }   
      },
      columns: [{
        data: 'site_code',
        type: 'text'
      }, {
        data: 'name',
        type: 'text',
      }, {
        data: 'city',
        type: 'text',
      }, {
        data: 'locality',
        type: 'text',
      }, {
        data: 'width',
        type: 'numeric'
      }, {
        data: 'length1',
        type: 'numeric'
      }, {
        data: 'monitor',
        type: 'dropdown',
        source: <?php echo json_encode($this->users);?>
            }]
          });
          



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


 var handsontable = $('#listings_campaign').data('handsontable');
  handsontable.loadData(null);
  var parsedData = JSON.parse($('#sidedata').html());
  handsontable.loadData(parsedData);
});

function fetchSites() {
  var handsontable = $('#listings_campaign').data('handsontable');
  handsontable.loadData(null);
  var parsedData = JSON.parse($('#sidedata').html());
  handsontable.loadData(parsedData);
}

function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
function cleanTableData(data) {
  var cleanData = [];
  var changedata1 = changedata.filter(onlyUnique);

  data.forEach(function(row){
    if(!row.id && row.name && row.city && row.locality) {
      cleanData.push({
        site_code: row.site_code,
        name: row.name,
        mediatype: row.mediatype,
        locality: row.locality,
        city: row.city,
        length: row.length1,
        width: row.width,
        lighting: row.lighting,
        monitor: row.monitor,
      });
    }
  });
  console.log('cleanData : ' + cleanData.length);
  for (var i = 0; i < changedata1.length; i++) {
    var row = data[changedata1[i]];
    if (row.name && row.city && row.locality) {
      cleanData.push({
        id: row.id,
        site_code: row.site_code,
        name: row.name,
        locality: row.locality,
        city: row.city,
        length: row.length1,
        width: row.width
      });
    } 
  }
  console.log('cleanData2 : ' + JSON.stringify(cleanData));
  return cleanData;
}

var addSitesToCampaign = function () {
//    $('#campaign_creation_modal').modal("hide");
//    $('#flash-messages').show('slow', function() {
//    });
    
      jQuery('#loading-image').show();        
  var handsontableData = $('#listings_campaign').data('handsontable').getData();
  var cleanData = cleanTableData(handsontableData);
  console.log(cleanData);
//  if (byuserid) {
var cid = $('#createdcampaignid').html();
    if(cleanData.length && cid.length) {
      $.ajax({
        type: 'POST',
        url: $('#completePath').text() +'/ajax/massuploadsiteForCampaign',
        data: {
         // 'byuserid': byuserid,
          'cid' : cid, 
          'data': JSON.stringify(cleanData)
        },
        success: function(data) {
          jQuery('#loading-image').hide();
          if (data) {
              
              //console.log(data);
            window.location.href = $('#completePath').text()+  '/myCampaigns';
        } else
            alert('Failed to save data.')
        },
        error: function(data) {
          jQuery('#loading-image').hide();
         // location.reload();
        }
      });
    } else {
        alert('Please enter sites data');
    }
//  } else {
//    alert("Please select Media Vendor from the drop down");
//    $(window).scrollTop(0);
//    $('#vendor-ac').focus();
//  }
};
</script> 