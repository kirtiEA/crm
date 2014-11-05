<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/campaigns.js"></script>
<!-- add new user sub-header -->    
<script>
$('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
</script>
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
    <li class="active"><a href="#home" >Created by Me</a></li>
    <li><a href="<?php echo Yii::app()->createUrl('assignedCampaigns');?>" >Assigned to Me</a></li>
        
    <li ><a href="<?php echo Yii::app()->createUrl('sharedWithMe');?>" >Shared With Me</a></li>
</ul>

<!-- campaigns list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="#"> <button type="button" class="btn btn-default active" >Active <span class="cnt1"></span></button></a>
                <a href="<?php echo Yii::app()->createUrl('myCampaigns/upcoming'); ?>"><button type="button" class="btn btn-default" >Upcoming <span class="cnt2"></span></button></a>
                <a href="<?php echo Yii::app()->createUrl('myCampaigns/expired'); ?>"><button type="button" class="btn btn-default" > Expired <span class="cnt3"></span></button></a>
            </div>
            <h1 class="list-heading">Campaign List (<?php echo count($campaigns)?>)</h1>
            <ul class="list">
            <?php 
                $html = '';
                foreach ($campaigns as $value) {
                    $html = $html . '            <li class="list-item">
                <h2 class="list-item-heading clickfor-show-hide pull-left"><span class="glyphicon glyphicon-plus expand-collapse"></span>&nbsp;' . $value['name'] . ' (' . $value['count'] .')</h2>'
                 . '<h3><i>&nbsp;&nbsp;' . $value['startDate'] .'-'. $value['endDate'] .'</i></h3>' .
                  '<div class="pull-right campaign-btn">
                    <button data-toggle="modal" data-target="#share-campaign-modal" onclick="$(\'#selectedShareCampaign\').val('. $value['id'] .')" class="btn btn-secondary"><span class="glyphicon glyphicon-share"></span> Share</button>
                </div>' .
                  '<div class="list-item-content show-hide-content" style="display:none;">
                    <ul class="sub-list abc" >';
                    foreach ($value['sites'] as $site) {
                        $html = $html . '<li>
                            <h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-plus expand-collapse"></span>&nbsp;' . $site['name'] . ' &nbsp;</h3>'
                            . '<ul class="sub-sub-list show-hide-content" style="display:none;">';
                        foreach ($site['listings'] as $list) {
                            $html = $html . '<li>' . $list['name'] //. ', ' . $list['mediatype'] . ', '
                                    //. $list['locality'] 
                                    . '&nbsp;</li>';
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