<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/campaigns.js"></script>
<!-- add new user sub-header -->    
<script>
$('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');



    
 function  redirectToAddSites(id) {
     window.location.href = $('#completePath').text()+  '/myCampaigns/addsites?cid=' + id;
 }
</script>

<!-- end of add new user sub-header --> 

<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#home" >Created by Me</a></li>
<!--    <li><a href="<?php //echo Yii::app()->createUrl('assignedCampaigns');?>" >Assigned to Me</a></li>-->
        
    <li ><a href="<?php echo Yii::app()->createUrl('sharedWithMe');?>" >Shared With Me</a></li>
    <li class="pull-right"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#campaign_creation_modal">+ Add New Campaign</button></li>
</ul>

<!-- campaigns list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="<?php echo Yii::app()->createUrl('myCampaigns'); ?>"> <button type="button" class="btn btn-default innerfilter active" id="filterActive">Active <span class="cnt1"></span></button></a>
                <a href="<?php echo Yii::app()->createUrl('myCampaigns?t=2'); ?>"><button type="button" class="btn btn-default innerfilter" id="filterUpcoming">Upcoming <span class="cnt2"></span></button></a>
                <a href="<?php echo Yii::app()->createUrl('myCampaigns/expired'); ?>"><button type="button" class="btn btn-default innerfilter" > Expired <span class="cnt3"></span></button></a>
            </div>
            <h1 class="list-heading">Campaign List (<?php echo count($campaigns)?>)</h1>
            <ul class="list">
                  <?php 
            //<button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Change Site Assignment</button>&nbsp;
                $html = '';
                foreach ($campaigns as $value) {
                $html = $html . '            <li class="list-item list-item-block" id="camp_'. $value['id'] .'">
                <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $value['name'] . ' (' . $value['count'] .')</h2>'
                 . '<span><i>&nbsp;&nbsp;' . $value['startDate'] .'-'. $value['endDate'] .'</i></span>' .
                  '<span class="pull-right">
                      
                    <button class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                    &nbsp;' 
                        .
//                        '&nbsp;
//                        <button class="btn btn-primary" data-toggle="modal" data-target="#add-campaign-modal" onclick="saveCampaign(\'' .$value['id'] . '\');">Save Campaign</button>'
                        '<button class="btn btn-secondary" onclick="redirectToAddSites(\''. $value['id'] .'\''.');"><span class="glyphicon glyphicon-plus"></span> Add Sites</button>
                        &nbsp;'
//                        <button class="btn btn-primary" data-toggle="modal" data-target="#add-campaign-modal" onclick="saveCampaign(\'' .$value['id'] . '\');">Save Campaign</button>
                .'</span>' .
                  '<div class="list-item-content show-hide-content">
                    <ul class="sub-list" id="campaign_'. $value['id'].'">';
                    foreach ($value['sites'] as $site) {
                        $html = $html . '<li id="vendorselected_'. $value['id'] . '_' . $site['id'] .'">
                            <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $site['name'] .'('.$site['count'] . ') &nbsp;</h3>'
//                            . '<div class="assign-dropdown">Assigned to 
//                                <select>
//                                    <option value="'. $site['id'].'_0" selected="true">Myself</option>';
//if (strcasecmp($site['id'], Yii::app()->user->cid) != 0) {
//                            $html = $html . '<option value="'.$site['id']. '_' .$site['id'] .'">' . $site['name'] .'</option>';
//                        }
//                                    
//                          $html = $html .      '</select></div>' 
                                
                            . '<ul class="sub-sub-list show-hide-content">';
                        foreach ($site['listings'] as $list) {
                            $html = $html . '<li>' . $list['name']; //. ', ' . $list['mediatype'] . ', '
                                    //. $list['locality'] 
                                    //. '&nbsp; <span onclick="removeListingFromCampaignd(\'' . $list['id'] .'\', \'' . $value['id'] .'\');" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_'.$list['id'].'"></span>'
                            $html = $html . '<div class="assign-dropdown">Assigned to 
                                <select id="selectcampaign_'.$value['id'].'_' . $list['id'] . '" onchange="assignTaskToUser(\''. $value['id'] .'\',\''. $list['id'] .'\');">';        
                            if(!empty($list['assignedusers'])) {
                                
                                foreach ($users as $user) {
                                    if (strcasecmp($list['assignedusers']['id'], $user['id']) == 0) {
                                        $html = $html . '<option value="'. $user['id'] .'" selected="true">'. $user['name'] .'</option>';
                                    } else {
                                        $html = $html . '<option value="'. $user['id'] .'" >'. $user['name'] .'</option>';
                                    }
                                }
                            } else {
                                $html = $html . '<option value="0" selected="true">UnAssigned</option>';
                                foreach ($users as $user) {
                                    $html = $html . '<option  value="'. $user['id'] .'" >'. $user['name'] .'</option>';
                                }
                            }
                            $html = $html .      '</select></div>';
                            $html = $html .  '&nbsp; <span onclick="removeListingFromCampaignd(\'' . $list['id'] .'\', \'' . $value['id'] .'\');" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_'.$list['id'].'"></span>';
                            $html = $html .  '</li>';
                        }
                        $html = $html . '</ul></li>';
                    }
                    $html = $html . '</ul></div></li>';
                }        
                
                        
            echo $html;
            ?>    
            </ul>
                
                

                
                        
                            
<!--                            <div class="assign-dropdown">Assigned to
                                <select>
                                    <option>Selvel</option>
                                    <option>Myself</option>
                                </select>
                            </div>-->
                            
                            
                       
                </div>
        </div>
    </div>
</div>
<!-- end of campaigns list --> 
<script>
$('.innerfilter').each(function() {
        $(this).removeClass('active');
    }); 
var t = '<?php echo Yii::app()->request->getParam('t');?>';
if (t == 2) {
    $('#filterUpcoming').addClass('active');
} else {
    $('#filterActive').addClass('active');
}
</script>
<?php $this->widget('CampaignModal'); ?>
