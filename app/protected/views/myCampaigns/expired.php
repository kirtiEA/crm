
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/campaigns.js"></script>

<!-- add new user sub-header -->    
<script>
$('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
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
   <a href="<?php echo Yii::app()->createUrl('myCampaigns'); ?>"> <button type="button" class="btn btn-default innerfilter " id="filterActive">Active <span class="cnt1"></span></button></a>
                <a href="<?php echo Yii::app()->createUrl('myCampaigns?t=2'); ?>"><button type="button" class="btn btn-default innerfilter" id="filterUpcoming">Upcoming <span class="cnt2"></span></button></a>
                <a href="#"><button type="button" class="btn btn-default active" > Expired <span class="cnt3"></span></button></a> 
            </div>
            <h1 class="list-heading">Campaign List (<?php echo count($campaigns)?>)</h1>
            
            
            
            
            
            <ul class="list">
            <?php 
                // A report for <Campaign Name> from <Start Date> to <Curr Date or End Date whichever is lower> will be generated in PDF format                
                $html = '';
                foreach ($campaigns as $value) {
                    $html = $html . '            <li class="list-item">
                <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $value['name'] . ' (' . $value['count'] .')</h2>'
                 . '<h3><i>&nbsp;&nbsp;' . $value['startDate'] .'-'. $value['endDate'] .'</i></h3>' .
                  '<div class="btnadjust pull-right">
                    <button data-toggle="modal" data-target="#share-campaign-modal" onclick="$(\'#selectedShareCampaign\').val('. $value['id'] .')" class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                </div>' .
                  '<div class="list-item-content show-hide-content">
                    <ul class="sub-list">';

                    foreach ($value['sites'] as $site) {
                        $html = $html . '<li>
                            <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' . $site['name'] .'('.$site['count'] . ') &nbsp;</h3>'
                            . '<ul class="sub-sub-list show-hide-content">';
                        foreach ($site['listings'] as $list) {
                            $html = $html . '<li>' . $list['name'] . '&nbsp;</li>';
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

<script type="text/javascript">
    $('.down_rep_but').click(function(){
        var r = window.confirm('Download report');
        if(r == true)
            alert('OK');
        else 
            alert('Cancel');
    });
</script>
