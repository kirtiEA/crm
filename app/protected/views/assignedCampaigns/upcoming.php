<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/campaignListings.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/vendors.js"></script>

<!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/campaigns.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/addedListingsToCampaign.js"></script>-->

<script type="text/javascript">
    function fetchCampaigns(type){
        $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/fetchCampaigns'); ?>',
                   data: {
                       'type': type
                   },
                success:function(data){
dust.render("campaigns", JSON.parse(data) , function(err, out) {
            $('#campaigns').html(out);
            var cnt = JSON.parse(data);
            //console.log(cnt.length + " sdfsfsdfsfsddsfdsfsd") ;
            $('.cnt'+type).html('(' + cnt.length + ')');
            $('.cnt').html(cnt.length);
            console.log(err);
            //expand collapse content  
            $('.clickfor-show-hide').click(function(e) {
//                $(this).toggle();

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
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
         
    }
    
    function fetchCampaignDetails(id) {
        $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/campaignDetails'); ?>',
                   data: {
                       'cid': id
                   },
                success:function(data){
                    $('#add-site-modal').modal('hide');
                    $('.selectedCampaignId').html('');
                    $('#campaignListings').html('');
                     dust.render("addedListingsToCampaign", JSON.parse(data), function(err, out) {
                    $('#campaign_' + id).html(out);
                        console.log(err);
                    });    
//                                  $(this).siblings('.show-hide-content').each(function() {
//                    $(this).toggle();
//                });
                    //console.log(data);  
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
    }
        function onlyUnique(value, index, self) { 
            return self.indexOf(value) === index;
        }
    var addtocampaign = [];
    var removefromcampaign = [];
    function addToCampaign(id) {
        addtocampaign.push(id);
        $('#listing_' + id).addClass('selected');
        $('#listing_' + id + ' span').removeClass('glyphicon-plus').addClass('glyphicon-remove').attr('onclick', 'removeFromArrayAddToCampaign(\'' + id + '\')');
        var cid = $('#selectedvendorid').val();
        var details = $('#listing_'+id).text();
        var html = '<li id="justadded_' + id +'">' + details +'<span onclick="removeFromArrayAddToCampaign(\'' + id + '\')" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_1"></span></li>';
        console.log('cid ' + cid + ' html ' + html);
        $('#vendorselected_'+cid + ' > ul').append(html);
    }
    function removeFromArrayAddToCampaign(id) {
            var index = addtocampaign.indexOf(id);
            if (index > -1) {
                $('#listing_' + id).removeClass('selected');
                $('#listing_' + id + ' span').removeClass('glyphicon-remove').addClass('glyphicon-plus').attr('onclick', 'addToCampaign(\'' + id + '\')');
                $('#justadded_' + id).remove();
                addtocampaign.splice(index, 1);
            }
    }
    function removeFromCampaign(id) {
        removefromcampaign.push(id);
        $('#listing_' + id).removeClass('selected');
    }
    
    function saveCampaign(id) {
    $('.selectedCampaignId').html(id);
    }    
    function finalCampaignSave() {
        var id = $('.selectedCampaignId').html();
    var temp = $('#campaign_' + id+' > li > div > select > option:selected').map(function(){ return $(this).val();
    }).get();
    var selCam = [];
        for (var i =0; i < temp.length; i++) {
            if (temp[i] != '0') {
                selcam.push(temp[i]);
            }
        }
        
    //for all the selcam ids create pop tasks
    
    }
    
    function finalCampaignUpdate(type,pop) {
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->urlManager->createUrl('ajax/updateCampaign'); ?>',
                    data: {
                        'cid': $('.selectedCampaignId').html(),
                        'add':JSON.stringify(addtocampaign.filter( onlyUnique )),
                        'rm':JSON.stringify(removefromcampaign.filter( onlyUnique )),
                        'type' : type,
                        'pop' : pop
                    },
                 success:function(data){
                     
                     
                     
                     $('#add-site-modal').modal('hide');
                            //$('.selectedCampaignId').html('');
                            $('#campaignListings').html('');
                            console.log(data);
                        if (data === '200') {
                            location.reload();
                        }    
                        
                    },
                    error: function(data) { // if error occured
                          alert("Error occured.please try again");
                          alert(data);
                     }
                   });
    }    
    
    function updateCampaign() {
        var chk = [];
        $("input:checkbox[name=pop]:checked").each(function()
        {
           chk.push($(this).val());
        });
        var id = $('.selectedCampaignId').text();
        
    var temp = $('#campaign_' + id+' > li > div > select > option:selected').map(function(){ return $(this).val();
    }).get();
        console.log(chk + " cmap " + id + ' fdfd '+ temp);
      //  $('.selectedCampaignId').html(id);
        if (chk.length > 0) {
            if (chk.length == 1 && chk[0] == '2') {
                var r = confirm("Are you sure you don't want proof of posting from vendors?");
                if (r == true) {
                 //type 2   
                    finalCampaignUpdate(2,null);
                } else {
                    txt = "You pressed Cancel!";
                }
            } if (chk.length == 1 && chk[0] == '1') {
                //type 1
                finalCampaignUpdate(1,JSON.stringify(temp));
            } else if (chk.length == 2){
                //type 3
                finalCampaignUpdate(3,JSON.stringify(temp));
            }    
        } else {
            alert("Please Select atleast one option");
        }    
    }
    
    function fetchvendors(name,id) {
        $('.campaignName').html(name);
        $('.selectedCampaignId').html(id);
         $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->urlManager->createUrl('ajax/vendorsList'); ?>',
         success:function(data){
             dust.render("vendors", JSON.parse(data), function(err, out) {
                 $('#vendors').html(out);
                 console.log(err);
             });    
            },
            error: function(data) { // if error occured
                  alert("Error occured.please try again");
                  alert(data);
             }
           });
    }
    
    function fetchCompanyListings(id) {
        
        $('.vendorselection').each(function() {
            $(this).removeClass('selected');
        });
        $('vendor_'+ id).addClass('selected');
        
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->urlManager->createUrl('ajax/fetchVendorListing'); ?>',
            data: {
                'id' : id,
                'cid' : $('.selectedCampaignId').html()
            },
         success:function(data){
             dust.render("campaignListings", JSON.parse(data), function(err, out) {
                 $('#campaignListings').html(out);
                 $('#selectedvendorid').val(id);
                 console.log(err);
                 var arr = $('#campaignListings > li').map(function(){ return $(this).attr('id').split('_')[1];
            }).get();
            
            
            for (var i=0; i < addtocampaign.length; i++) {
                for (var j=0; j<arr.length;j++) {
                    if (addtocampaign[i] == arr[j]) {
                        $('#listing_' + arr[j]).addClass('selected');
                        $('#listing_' + arr[j] + ' span').addClass('glyphicon-remove').removeClass('glyphicon-plus').attr('onclick', 'removeFromArrayAddToCampaign(\'' + arr[j] + '\')');
                    }    
                }    
            }   
                 
             });    
            },
            error: function(data) { // if error occured
                  alert("Error occured.please try again");
                  alert(data);
             }
           });
    }
    $('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
</script>
<span class="selectedCampaignId" style="display: none;"></span>
<!-- Campaign Confirmation Modal -->
    <div class="modal fade" id="add-campaign-modal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h2 class="modal-title"><b>Save Campaign</b>   </h2>
          </div>
          <div class="modal-body">
              <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table>
                            <tr>
                                <td>Request Assigned Vendors for Proof of Posting</td>
                                <td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="pop" value="1" checked="true"></td>
                            </tr>
                            <tr>
                                <td>Create Daily Monitoring Plan for my team</td>
                                <td>&nbsp;&nbsp;&nbsp;<input type="checkbox" value="2" name="pop" ></td>
                            </tr>
                        </table>
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal">Cancel&nbsp;</a>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="updateCampaign();">Save</button>
          </div>
        </div>
      </div>
    </div>  


<!-- Add Site Large Modal -->
    <div class="modal fade" id="add-site-modal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-extra-large">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h2 class="modal-title"><b>Add Sites</b> to <span class="campaignName"></span> </h2>
          </div>
          <div class="modal-body">
              <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 left-content">
                        <div class="search-box-wrapper">
                            <input id="selectedvendorid" type="hidden">
                            <input type="text" placeholder="Search Vendor">
                        </div>
                        <ul id="vendors">
                            
                        </ul>
                    </div>
                    <div class="col-md-9 right-content" style="height: 700px;">
                        <div class="search-box-wrapper">
                            <input type="text" placeholder="Search Sites">
                        </div>
                        <ul id="campaignListings">
                        </ul>
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal">Cancel&nbsp;</a>
            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="$('#')" >Add Sites</button>
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
    <li><a href="<?php echo Yii::app()->urlManager->createUrl('myCampaigns')?>" >Created by Me</a></li>
    <li class="active"><a href="#profile" >Assigned to Me</a></li>
</ul>

<!-- campaigns list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="<?php echo Yii::app()->createUrl('assignedCampaigns'); ?>"><button type="button" class="btn btn-default" >Active <span class="cnt1"></span></button></a>
                <a href="#"><button type="button" class="btn btn-default active" >Upcoming <span class="cnt2"></span></button></a>
                <a href="<?php echo Yii::app()->createUrl('assignedCampaigns/expired'); ?>"><button type="button" class="btn btn-default " > Expired <span class="cnt3"></span></button></a> 
            </div>
            <h1 class="list-heading">Campaign List (<?php echo count($campaigns)?>)</h1>
            <ul class="list">
            <?php 
                $html = '';
                foreach ($campaigns as $value) {
                    $html = $html . '            <li class="list-item">
                <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $value['name'] . ' (' . $value['count'] .')</h2>'
                 . '<h3><i>&nbsp;&nbsp;' . $value['startDate'] .'-'. $value['endDate'] .'</i></h3>' .
                  '<div class="pull-right">
                    <button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                    &nbsp;
                        <button class="btn btn-secondary" data-toggle="modal" data-target="#add-site-modal" onclick="fetchvendors(\''. $value['name'] .'\', \''.$value['id'] .'\');"><span class="glyphicon glyphicon-plus"></span> Add Sites</button>
                        &nbsp;
                        <button class="btn btn-primary" data-toggle="modal" data-target="#add-campaign-modal" onclick="saveCampaign(\'' .$value['id'] . '\');">Save Campaign</button>
                </div>' .
                  '<div class="list-item-content show-hide-content">
                    <ul class="sub-list" id="campaign_'. $value['id'].'">';
                    foreach ($value['sites'] as $site) {
                        $html = $html . '<li id="vendorselected_'. $site['id'] .'">
                            <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $site['name'] .'('.$site['count'] . ') &nbsp;</h3>'
                            . '<div class="assign-dropdown">Assigned to 
                                <select>
                                    <option value="'. $site['id'].'_0" selected="true">Myself</option>
                                    <option value="'.$site['id']. '_' .$site['id'] .'">' . $site['name'] .'</option>
                                </select></div>'
                                
                            . '<ul class="sub-sub-list show-hide-content">';
                        foreach ($site['listings'] as $list) {
                            $html = $html . '<li>' . $list['name'] . ', ' . $list['mediatype'] . ', '
                                    . $list['locality'] . '&nbsp; <span onclick="removeListingFromCampaignd(\'' . $list['id'] .'\', \'' . $value['id'] .'\');" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_'.$list['id'].'"></span></li>';
                        }
                        $html = $html . '</ul></li>';
                    }
                    $html = $html . '</ul></div></li>';
                }        
                
                        
            echo $html;
            ?>    
            </ul>
        </div>
    </div>
</div>
<!-- end of campaigns list --> 
