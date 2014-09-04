<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'vendor_subscription',
    'action' => 'subscription/createvendor',
    //'enableClientValidation' => true,
//    'clientOptions' => array(
//        'validateOnSubmit' => true,
//    ),
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
        ));
?>
<div>
    <h3>Subscription Form</h3>
    <div>
        <label class="cname">Company Name</label>
        <?php echo $form->textField($model, 'companyname'); ?> 
        <input type="hidden" value="" id="vendor-ac-id">
        <?php echo $form->error($model, 'companyname'); ?>
    </div>
    <div>
        <label class="email">Email ID</label>
        <?php echo $form->textField($model, 'email'); ?>                            
        <?php echo $form->error($model, 'email'); ?>
    </div>
    <div>
        <label class="phn">Phone Number</label>
        <?php echo $form->textField($model, 'phonenumber'); ?>                            
        <?php echo $form->error($model, 'phonenumber'); ?>
    </div>
    <?php echo $form->hiddenField($model, 'nid',array('value'=>$nid, 'id' =>'nid'));?>
    <?php echo CHtml::submitButton('Save', array('class' => 'save', 'id' => '_submit')); ?>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(function() {

        //autocomplete for company name in vendor subscription form
        var allVendorJson = JSON.parse('<?php echo $vendorList; ?>');
        //console.log(allVendorJson);
        $('.cname').autocomplete({
            source: allVendorJson,
            select: function(event, ui) {
                console.log(ui.item.value + ', ' + ui.item.id);
                $("#vendor-ac-id").val(ui.item.id);
                fetchSites(ui.item.id);
            },
            change: function(event, ui) {
                if (ui.item == null) {
                    $(".cname").val('');
                    $("#vendor-ac-id").val('');
                    $(".cname").focus();
                }
            },
            messages: {
                noResults: '',
                results: function() {
                }
            },
        })
    });
</script>