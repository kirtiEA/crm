<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.handsontable.full.css" />
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.handsontable.full.js"></script>

<!--
    <link rel="stylesheet" media="screen" href="http://handsontable.com/lib/jquery-ui/css/ui-bootstrap/jquery-ui.custom.css">
    <script src="http://handsontable.com/lib/jquery-ui/js/jquery-ui.custom.min.js"></script>
-->

<!-- tasks list --> 
<div class="container-fluid content-wrapper content-wrapper-custom">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading">Add Vendors Sites</h1>
            <label>Select Vendor </label>
            <input type="text" placeholder="Enter a Vendor Name" id="vendor-ac">
            <input type="hidden" value="" id="vendor-ac-id">
            <a href="#">Can't find a vendor? <b>Invite him</b></a>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="upload-holder" id="listings"></div>
                </div>
            </div>
            <div class="container-fluid table-control">
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <a href="#" class="disabled">Cancel&nbsp;</a>
                            <button type="button" name="save" data-dump="#listings" class="btn btn-primary">Save</button>
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
        var handsontable = $('#listings').data('handsontable');
        $.ajax({
            url: "<?php echo Yii::app()->urlManager->createUrl('ajax/fetchvendorsites'); ?>",
            type: "POST",
            data: {
                vendorid: vendorid
            },
            async: false,
            beforeSend: function() {
                // clear the table
                handsontable.loadData(null);
            },
            success: function(data) {
                var parsedData = JSON.parse(data);
                handsontable.loadData(parsedData);
                if(parsedData)
                    console.log(parsedData.length);
            }
        });
    }
    $('#listings').handsontable({
        //data: [['','','','']],
        minCols: 20,
        colHeaders: ['SITE CODE', 'MEDIA TYPE', 'CITY', 'LOCALITY', 'NAME', 'LENGTH', 'WIDTH', 'LIGHTING'],
        rowHeaders: true,
        colWidths: [100, 150, 150, 250, 250, 100, 100, 100],
        manualColumnResize: true,
        manualRowResize: true,
        minSpareRows: 50,
        columns: [{
                data: 'site_code',
                type: 'text'
            }, {
                data: 'mediatype',
                type: 'dropdown',
                source: <?php echo $mediaType; ?>
            }, {
                data: 'city',
                type: 'text'
            }, {
                data: 'locality',
                type: 'text'
            }, {
                data: 'name',
                type: 'text'
            }, {
                data: 'length1',
                type: 'numeric'
            }, {
                data: 'width',
                type: 'numeric'
            }, {
                data: 'lighting',
                type: 'dropdown',
                source: <?php echo $lightingType; ?>
            }]
                /*cells: function(r,c, prop) {
                 var cellProperties = {};
                 if (r===0) cellProperties.readOnly = true;
                 return cellProperties;
                 }*/
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function cleanTableData(data) {
        //console.log(data);
        var cleanData = [];
        data.forEach(function(row){
            if(row.name) {
                cleanData.push({
                    site_code: row.site_code,
                    name: row.name,
                    mediatype: row.mediatype,
                    locality: row.locality,
                    city: row.city,
                    length: row.length1,
                    width: row.width,
                    lighting: row.lighting                    
                });
            }
        });
        return cleanData;
    }
    $('body').on('click', 'button[name=save]', function() {
        //jQuery('#loading-image').show();        
        var handsontableData = $('#listings').data('handsontable').getData();
        var cleanData = cleanTableData(handsontableData);
        //console.log('data of ' + dump, JSON.stringify($container.handsontable('getData')));        
        
        //console.log(JSON.stringify(handsontableData));
        var vendorid = $('#vendor-ac-id').val();
        var byuserid = 1;//'<?php echo Yii::app()->user->id; ?>';
        console.log(vendorid + " - " + byuserid + " - " + cleanData.length);
        console.log(cleanData);
        
        if (vendorid && byuserid && cleanData.length) {
            console.log('inside if');
            $.ajax({
                type: 'POST',
                url: '<?php echo Yii::app()->urlManager->createUrl('ajax/massuploadsite'); ?>',
                data: {
                    'vendorid': vendorid,
                    'byuserid': byuserid,
                    'data': JSON.stringify(cleanData)
                },
                success: function(data) {
                    //var json = JSON.parse(data);
                    if(data == true)
                        alert('Data saved.')
                    else 
                        alert('Failed to save data.')
                },
                error: function(data) { // if error occured
                    alert("Error occured.please try again");
                    alert(data);
                }
            });
        } else {
            alert("Please select Media Vendor's Email Address");
        }


    });
    $(function() {
        $('.mon_menu').each(function() {
            $(this).removeClass('active');
        });
        $('.menu_site').addClass('active');

        var allVendorJson = JSON.parse('<?php echo $vendorList; ?>');
        $('#vendor-ac').autocomplete({
            source: allVendorJson,
            select: function(event, ui) {
                console.log(ui.item.value + ', ' + ui.item.id);
                $("#vendor-ac-id").val(ui.item.id);
                fetchSites(ui.item.id);                
            },
            change: function(event, ui) {
                if (ui.item == null) {
                    $("#vendor-ac").val('');
                    $("#vendor-ac-id").val('');
                    $("#vendor-ac").focus();                    
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