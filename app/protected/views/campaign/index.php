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
                    $('#selectedCampaignId').html('');
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
    
    function updateCampaign() {
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->urlManager->createUrl('ajax/updateCampaign'); ?>',
            data: {
                'cid': $('#selectedCampaignId').html(),
                'add':JSON.stringify(addtocampaign.filter( onlyUnique )),
                'rm':JSON.stringify(removefromcampaign.filter( onlyUnique ))
            },
         success:function(data){
             //selectedCampaignId campaignListings to be removed
                    $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/campaignDetails'); ?>',
                   data: {
                       'cid': $('#selectedCampaignId').html()
                   },
                success:function(data){
                    $('#add-site-modal').modal('hide');
                    $('#selectedCampaignId').html('');
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
    
    function fetchvendors(name,id) {
        $('#campaignName').html(name);
        $('#selectedCampaignId').html(id);
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
                'cid' : $('#selectedCampaignId').html()
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

<!-- Add Site Large Modal -->
    <div class="modal fade" id="add-site-modal" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-extra-large">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h2 class="modal-title"><b>Add Sites</b> to <span id="campaignName"></span><span id="selectedCampaignId" style="display: none;"></span> </h2>
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
            <button type="button" class="btn btn-primary" onclick="updateCampaign();">Add Sites</button>
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
