<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/campaigns.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/vendors.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/campaignListings.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/addedListingsToCampaign.js"></script>

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
    function removeListingFromCampaign(id) {
        
        var cid = $('#addedlistings_' +id).parent().parent().parent().parent().attr('id').split('_')[1];
        $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/removeListingFromCampaign'); ?>',
                   data: {
                       'sid': id,
                       'cid' : cid
                   },
                success:function(data){
              $('#addedlistings_' + id).parent().remove();
                    console.log(data);  
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
        
    }
    function removeFromArrayAddToCampaign(id) {
            var index = addtocampaign.indexOf(id);
            if (index > -1) {
                $('#listing_' + id).removeClass('selected');
           $('#listing_' + id + ' span').removeClass('glyphicon-remove').addClass('glyphicon-plus').attr('onclick', 'addToCampaign(\'' + id + '\')');
                addtocampaign.splice(index, 1);
            }
    }
    function removeFromCampaign(id) {
        removefromcampaign.push(id);
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
                     //selectedCampaignId campaignListings to be removed
                            $.ajax({
                           type: 'POST',
                           url: '<?php echo Yii::app()->urlManager->createUrl('ajax/campaignDetails'); ?>',
                           data: {
                               'cid': $('.selectedCampaignId').html()
                           },
                        success:function(data){
                            $('#add-site-modal').modal('hide');
                            //$('.selectedCampaignId').html('');
                            $('#campaignListings').html('');
                             dust.render("vendors", JSON.parse(data), function(err, out) {
                            $('#vendors').html(out);
                                console.log(err);
                            });    
                            //console.log(data);  
                           },
                           error: function(data) { // if error occured
                                 alert("Error occured.please try again");
                                 alert(data);
                            }
                          });

                     //console.log(data);  
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
                 console.log(err);
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
    $(document).ready(function() {
            dust.render("campaigns", <?php echo $campaigns;?>, function(err, out) {
                $('#campaigns').html(out);
            var cnt = <?php echo $campaigns;?>;
            //console.log(cnt.length + " sdfsfsdfsfsddsfdsfsd") ;
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
    });
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
<!--                    <div class="col-md-9 right-content">
                        <div class="search-box-wrapper">
                            <input type="text" placeholder="Search Sites">
                        </div>
                        <ul id="campaignListings">
                        </ul>
                    </div>-->
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal">Cancel&nbsp;</a>
            <button type="button" class="btn btn-primary" onclick="updateCampaign();">Save</button>
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
                            <input type="text" placeholder="Search Vendor">
                        </div>
                        <ul id="vendors">
                            
                        </ul>
                    </div>
                    <div class="col-md-9 right-content">
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
            <button type="button" class="btn btn-primary" >Add Sites</button>
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
                <button type="button" class="btn btn-default active" onclick="fetchCampaigns('1');">Active <span class="cnt1"></span></button>
                <button type="button" class="btn btn-default" onclick="fetchCampaigns('2');">Upcoming <span class="cnt2"></span></button>
                <button type="button" class="btn btn-default" onclick="fetchCampaigns('3');">Expired <span class="cnt3"></span></button>
            </div>
            <h1 class="list-heading">Campaign List (<span class="cnt"></span>)</h1>
            <ul class="list" id="campaigns">
                
            </ul>
        </div>
    </div>
</div>
<!-- end of campaigns list --> 
