
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mustache.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.10.2.custom.min.js"></script>
<!--<script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/jquery.tinysort.min.js"></script>
<script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/filter.js"></script>-->

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.handsontable.full.css" />
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.handsontable.full.js"></script>


<!--<script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/campaigns.js"></script>-->

<!--<script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/template/js/campaigns.js"></script>
<script type="text/javascript" src="<?php //echo Yii::app()->request->baseUrl; ?>/js/template/js/addedListingsToCampaign.js"></script>-->

<span class="selectedCampaignId" style="display: none;"></span>

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

<!--<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#home">Created by Me</a></li>
    <li><a href="<?php // echo Yii::app()->createUrl('assignedCampaigns');?>">Assigned to Me</a></li>
        
    <li ><a href="<?php //echo Yii::app()->createUrl('sharedWithMe');?>" >Shared With Me</a></li>
</ul>-->

<!-- campaigns list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
<!--                <a href="<?php //echo Yii::app()->createUrl('myCampaigns'); ?>"><button type="button" class="btn btn-default" >Active <span class="cnt1"></span></button></a>-->
<!--                <a href="#"><button type="button" class="btn btn-default active" >Upcoming <span class="cnt2"></span></button></a>-->
<!--                <a href="<?php echo Yii::app()->createUrl('myCampaigns/expired'); ?>"><button type="button" class="btn btn-default " > Expired <span class="cnt3"></span></button></a> -->
            </div>
            <h1 class="list-heading">Campaign List (<?php echo count($campaigns)?>)</h1>
            <ul class="list">
            <?php 
                $html = '';
                foreach ($campaigns as $value) {
                $html = $html . '            <li class="list-item panel" style="overflow-y: scroll;" id="camp_'. $value['id'] .'">
                <h2 class="list-item-heading clickfor-show-hide pull-left"><span onclick="fetchSites('.$value['id'].');" class="glyphicon glyphicon-plus expand-collapse"></span>&nbsp;' . $value['name'] . ' (' . $value['count'] .')</h2>'
                 . '<h3><i>&nbsp;&nbsp;' . $value['startDate'] .'-'. $value['endDate'] .'</i></h3> </span>' .
                  '<span class="btnadjust pull-right">
                    <button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                    &nbsp;
                        <button class="btn btn-primary" onclick="savecampaignnew(\'' .$value['id'] . '\');">Save Campaign</button>
                </span>' .
                  '<div class="list-item-content show-hide-content" style="display:none;" id="campaign_'. $value['id'].'">'; 
//                    <ul class="sub-list" >';
                
//                    foreach ($value['sites'] as $site) {
//                        $html = $html . '<li id="vendorselected_'. $value['id'] . '_' . $site['id'] .'">
//                            <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $site['name'] .'('.$site['count'] . ') &nbsp;</h3>'
//                            . '<div class="assign-dropdown">Assigned to 
//                                <select>
//                                    <option value="'. $site['id'].'_0" selected="true">Myself</option>';
//                        if (strcasecmp($site['id'], Yii::app()->user->cid) != 0) {
//                            $html = $html . '<option value="'.$site['id']. '_' .$site['id'] .'">' . $site['name'] .'</option>';
//                        }
//                                    
//                          $html = $html .      '</select></div>'
//                                
//                            . '<ul class="sub-sub-list show-hide-content">';
//                        foreach ($site['listings'] as $list) {
//                            $html = $html . '<li>' . $list['name'] //. ', ' . $list['mediatype'] . ', '
//                                    //. $list['locality'] 
//                                    . '&nbsp; <span onclick="removeListingFromCampaignd(\'' . $list['id'] .'\', \'' . $value['id'] .'\');" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_'.$list['id'].'"></span></li>';
//                        }
//                          
//                    
//                          
//                        $html = $html . '</ul></li>';
//                    }
                
                      $html = $html . '<div class="row"><div class="col-md-12"><span style="display:none;" id="data_'. $value['id'].'">'. json_encode($value['sites']) . 
                      '</span><div class="upload-holder" id="listings_'.$value['id'].'"></div>
                </div>
            </div>';
                    $html = $html . '</div></li>';
                }        
                
                        
            echo $html;
            ?>    
            </ul>
        </div>
    </div>
</div>

<script>
    var changedata = [];

    
    
     function fetchSites(cid) {
             $('#listings_'+cid).handsontable({
        //data: [['','','','']],
        //minCols: 20,
        colHeaders: ['SITE CODE', 'NAME', 'CITY', 'LOCALITY', 'WIDTH', 'HEIGHT', 'MONITOR'],
        rowHeaders: true,
        colWidths: [100, 150, 150, 250, 250, 100, 100, 100],
        
        currentRowClassName: 'currentRow',
        currentColClassName: 'currentCol',
        
        manualColumnResize: true,
        manualRowResize: true,
        minSpareRows: 2,
        onChange: function() {
            //console.log('onchange' + JSON.stringify(arguments));
            if (arguments[0] !== null) {
                changedata[changedata.length] = arguments[0][0][0];
//        changedata.push['asd'];
//        console.log(changedata + ' pushed ' + arguments[0][0][0]);
            } else {
                // console.log('null dda');
            }
//        changedata.push[arguments[0][0][0]];
//        for (var d = 0; d < arguments.length; d++) {
//            changedata.push[arguments[0][0][0]];
//            if (arguments[d] && arguments[d].length > 0) {
//            changedata.push[arguments[d][0][0]];
//            console.log('data - ' + JSON.stringify(changedata) + ' sdf '  + arguments[d][0][0]);
//            //break;
//            }    
//            
//            
//        }    
        },
        columns: [{
                data: 'site_code',
                type: 'text'
            }, {
                data: 'name',
                type: 'dropdown',
//                source: 
//                validator: non_empty_text,
//                allow_invalid: false
            }, {
                data: 'city',
                type: 'text',
//                validator: non_empty_text,
//                allow_invalid: false
            }, {
                data: 'locality',
                type: 'text',
//                validator: non_empty_text,
//                allow_invalid: false
            }, {
                data: 'width',
                type: 'numeric'
            }, {
                data: 'length1',
                type: 'numeric'
            }, {
                data: 'monitor',
                type: 'dropdown',
                source: <?php echo json_encode($users);?>
            }]
                /*cells: function(r,c, prop) {
                 var cellProperties = {};
                 if (r===0) cellProperties.readOnly = true;
                 return cellProperties;
                 }*/
    });
    
    
    
    
         
        var handsontable = $('#listings_'+cid).data('handsontable');
        handsontable.loadData(null);
        var parsedData = JSON.parse($('#data_'+cid).html());
        handsontable.loadData(parsedData);
//        $.ajax({
//            type: "POST",
//            data: {
//                vendorid: vendorid
//            },
//            async: false,
//            beforeSend: function() {
//                // clear the table
//                handsontable.loadData(null);
//            },
//            success: function(data) {
//                var parsedData = JSON.parse(data);
//                handsontable.loadData(parsedData);
//                //if (parsedData)
//                    //console.log(parsedData.length);
//            }
//        });
    }
    
    
        function cleanTableData(data) {
        //        console.log('change data s' + changedata);
        var cleanData = [];
        var changedata1 = changedata.filter(onlyUnique);
        //console.log('change data ' + changedata1);
        
        data.forEach(function(row){
            //console.log('index' + i);
            if(!row.id) {
                cleanData.push({
                    //id: row.id,
                    site_code: row.site_code,
                    name: row.name,
                    mediatype: row.mediatype,
                    locality: row.locality,
                    city: row.city,
                    length: row.length1,
                    width: row.width,
                    lighting: row.lighting                    
                });
            }
        });
        console.log('cleanData : ' + cleanData.length);
        for (var i = 0; i < changedata1.length; i++) {
            var row = data[changedata1[i]];
            if (row.name && row.mediatype && row.city && row.locality) {
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
//            else {
//                //console.log('i = ' + changedata1[i]);                 
//                // https://github.com/handsontable/jquery-handsontable/wiki/Methods
//                //validateCells('non_empty_text');
//                //$("#listings").handsontable('selectCell', changedata1[i], 3);
//                if(!row.mediatype) {
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 1, '*');
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 1, '');
//                }
//                if(!row.city) {
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 2, '*');
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 2, '');
//                }
//                if(!row.locality) {
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 3, '*');
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 3, '');
//                }
//                if(!row.name) {
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 4, '*');
//                    $("#listings").handsontable('setDataAtCell', changedata1[i], 4, '');
//                }
//            }
        }

//        data.forEach(function(row){
//            console.log('index' + i);
//            if(row.name) {
//                cleanData.push({
//                    id: row.id,
//                    site_code: row.site_code,
//                    name: row.name,
//                    mediatype: row.mediatype,
//                    locality: row.locality,
//                    city: row.city,
//                    length: row.length1,
//                    width: row.width,
//                    lighting: row.lighting                    
//                });
//            }
//        });
        console.log('cleanData2 : ' + cleanData.length);
        return cleanData;
    }
    function savecampaignnew(id) {
        jQuery('#loading-image').show();        
        var handsontableData = $('#listings_'+id).data('handsontable').getData();
        var cleanData = cleanTableData(handsontableData);
        //console.log('data of ' + dump, JSON.stringify($container.handsontable('getData')));        

        //console.log(JSON.stringify(handsontableData));
       // var vendorid = $('#vendor-ac-id').val();
        var byuserid = '<?php echo Yii::app()->user->id; ?>';
        //console.log(vendorid + " - " + byuserid + " - " + cleanData.length);
        //console.log(JSON.stringify(cleanData));

        if (byuserid) {
            //console.log('inside if');
            if(cleanData.length) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->urlManager->createUrl('ajax/massuploadsiteForCampaign'); ?>',
                    data: {
                        'byuserid': byuserid,
                        'data': JSON.stringify(cleanData)
                    },
                    success: function(data) {
                        //var json = JSON.parse(data);
                        //console.log(data);
                        jQuery('#loading-image').hide();
                        if (data == true)
                            location.reload();
                        else
                            alert('Failed to save data.')
                    },
                    error: function(data) { // if error occured
                        jQuery('#loading-image').hide();
                        //alert("Error occured.please try again");
                        //alert(data);
                        location.reload();
                    }
                });
            } else {
                
            }
        } else {
            alert("Please select Media Vendor from the drop down");
            $(window).scrollTop(0);
            $('#vendor-ac').focus();
        }
    }
</script>    
