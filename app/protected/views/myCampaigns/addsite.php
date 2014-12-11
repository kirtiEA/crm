<script>
$('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
     function  redirectToCampaign() {
     window.location.href = $('#completePath').text()+  '/myCampaigns';
 }
</script>
<div class="panel panel-default text-center panel-no-margin">
    <div class="panel-heading">
    <h1 class="text-success">Add Sites</h1>
    </div>
</div>
<div class="container content-wrapper" id="siteModalContent">
    <span id="createdcampaignid" class="hide"><?php echo $cid;?></span>
    <div class="row">
        <?php $this->widget('SiteModal'); ?>
    </div>

</div>
    <div class="text-center button-background">
    <button type="button" class="btn btn-success" onclick="redirectToCampaign();">Cancel</button>
 <button type="button" class="btn btn-danger " onclick="addSitesToCampaign()" id="FinishButtonCampaignModal">Finish</button>
    </div>
