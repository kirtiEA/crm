<!-- tasks list --> 
<div class="container-fluid content-wrapper content-wrapper-custom">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading">Add Vendors Sites</h1>
            <label>Select Vendor </label>
            <input type="text" placeholder="Enter a Vendor Name" id="vendor-ac">
            <a href="#">Can't find a vendor? <b>Invite him</b></a>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-container">
                        <table class="table table-bordered">
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Media Types</th>
                                <th>Dimensions</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="container-fluid table-control">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="#" class="disabled">Cancel&nbsp;</a>
                            <button type="button" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end of tasks list --> 
<script type="text/javascript">
    function fetchSites(vendorid) {
        console.clear();
        console.log(vendorid);
        $.ajax({
            url: "<?php echo Yii::app()->urlManager->createUrl('ajax/fetchvendorsites'); ?>",
            type: "POST",
            data: {
                vendorid: vendorid
            },
            async: false,
            beforeSend: function() {
                //console.log('before');                
            },
            success: function(data) {
                console.log(data);
            }
        });
    }
    $(function() {
        $('.mon_menu').each(function() {
            $(this).removeClass('active');
        });
        $('.menu_site').addClass('active');

        var allVendorJson = JSON.parse('<?php echo $vendorList; ?>');
        $('#vendor-ac').autocomplete({
            source: allVendorJson,
            select: function(event, ui) {
                //console.log(ui.item.value + ', ' + ui.item.id);
                fetchSites(ui.item.id);
            },
            change: function(event, ui) {
                if (ui.item == null) {
                    $("#newSiteVendor").val('');
                    $("#newSiteVendor").focus();
                }
            },
            messages: {
                noResults: '',
                results: function() {
                }
            },
        });
    });
</script>