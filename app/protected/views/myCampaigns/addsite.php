<script>
$('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');
</script>
<div class="col-xs-12 text-success text-center">
    <h1>Add Sites</h1>
</div>
<div class="container-fluid content-wrapper">
    <span id="createdcampaignid" class="hide"><?php echo $cid;?></span>
    <div class="row">
        <?php $this->widget('SiteModal'); ?>
    </div>
</div>
<button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
<button type="button" class="btn btn-danger " onclick="callMeSecondTime()" id="FinishButtonCampaignModal">Finish</button>