<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mustache.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.10.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.tinysort.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/filter.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/campaigns.js"></script>

<!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/campaigns.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/addedListingsToCampaign.js"></script>-->


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
                            <input id="selectedvendorname" type="hidden">
                            <input type="text" placeholder="Search Vendor" id="search_box_vendor">
                        </div>
                        <ul id="vendors">
                            
                        </ul>
                    </div>
                    <div class="col-md-9 right-content" style="height: 460px;">
                        <div class="search-box-wrapper">
                            <input type="text" placeholder="Search Sites" id="search_box_vendor_sites">
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
            //<button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Change Site Assignment</button>&nbsp;
                $html = '';
                foreach ($campaigns as $value) {
                $html = $html . '            <li class="list-item" id="camp_'. $value['id'] .'">
                <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $value['name'] . ' (' . $value['count'] .')</h2>'
                 . '<h3><i>&nbsp;&nbsp;' . $value['startDate'] .'-'. $value['endDate'] .'</i></h3>' .
                  '<div class="pull-right">
                      
                    <button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                    &nbsp;' .
                        '&nbsp;
                        <button class="btn btn-primary" data-toggle="modal" data-target="#add-campaign-modal" onclick="saveCampaign(\'' .$value['id'] . '\');">Save Campaign</button>'
//                        <button class="btn btn-secondary" data-toggle="modal" data-target="#add-site-modal" onclick="fetchvendors(\''. $value['name'] .'\', \''.$value['id'] .'\');"><span class="glyphicon glyphicon-plus"></span> Add Sites</button>
//                        &nbsp;
//                        <button class="btn btn-primary" data-toggle="modal" data-target="#add-campaign-modal" onclick="saveCampaign(\'' .$value['id'] . '\');">Save Campaign</button>
                .'</div>' .
                  '<div class="list-item-content show-hide-content">
                    <ul class="sub-list" id="campaign_'. $value['id'].'">';
                    foreach ($value['sites'] as $site) {
                        $html = $html . '<li id="vendorselected_'. $value['id'] . '_' . $site['id'] .'">
                            <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $site['name'] .'('.$site['count'] . ') &nbsp;</h3>'
                            . '<div class="assign-dropdown">Assigned to 
                                <select>
                                    <option value="'. $site['id'].'_0" selected="true">Myself</option>';
if (strcasecmp($site['id'], Yii::app()->user->cid) != 0) {
                            $html = $html . '<option value="'.$site['id']. '_' .$site['id'] .'">' . $site['name'] .'</option>';
                        }
                                    
                          $html = $html .      '</select></div>' 
                                
                            . '<ul class="sub-sub-list show-hide-content">';
                        foreach ($site['listings'] as $list) {
                            $html = $html . '<li>' . $list['name'] //. ', ' . $list['mediatype'] . ', '
                                    //. $list['locality'] 
                                    //. '&nbsp; <span onclick="removeListingFromCampaignd(\'' . $list['id'] .'\', \'' . $value['id'] .'\');" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_'.$list['id'].'"></span>'
                                    . '</li>';
                            if(!empty($list['assignedusers'])) {
                                $html = $html . '<div class="assign-dropdown">Assigned to ' . json_encode($list['assignedusers'])
                                    . '</div>';
                            }
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
<script id="vendorlist" type="text/html">
<li onclick="fetchCompanyListings('{{id}}');" class="vendorselection" id="vendor_{{id}}">{{name}} ({{cnt}}) </li>
</script>

<script id="vendorsitelist" type="text/html">
{{#is_onCampaign}}
<li class="selected" id="listing_{{id}}">{{name}}
<!--    , {{mediatype}}, {{locality}}-->
    <span class="glyphicon glyphicon-remove pull-right" onclick="removeFromCampaign('{{id}}');"></span>
</li>
{{/is_onCampaign}}
{{^is_onCampaign}}
<li id="listing_{{id}}">{{name}}
<!--    , {{mediatype}}, {{locality}}-->
    <span class="glyphicon glyphicon-plus pull-right" onclick="addToCampaign('{{id}}');"></span>
</li>    
{{/is_onCampaign}}
</script>
