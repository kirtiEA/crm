<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.handsontable.full.css" />
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/jquery.handsontable.full.js"></script>

<!--
    <link rel="stylesheet" media="screen" href="http://handsontable.com/lib/jquery-ui/css/ui-bootstrap/jquery-ui.custom.css">
    <script src="http://handsontable.com/lib/jquery-ui/js/jquery-ui.custom.min.js"></script>
-->
<script>
    var changedata = [];
</script>
<!-- invite vendor modal -->
<div class="modal fade" id="invite-vendor-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-sm-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h3><b>Invite Vendor</b></h3>
            </div>
            <div class="modal-body">
                <label>Vendor Email</label>&nbsp;
                <input class="email" type="email">
            </div>
            <div class="modal-footer">
                <a href="#" id="cancel">Cancel</a>&nbsp;
                <button class="invite btn btn-primary">Invite</button>
            </div>
        </div>
    </div>
</div>
<!-- end of invite vendor modal -->

<!-- tasks list --> 
<div class="container-fluid content-wrapper content-wrapper-custom">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading">Add Vendors Sites</h1>
            <label>Select Vendor </label>
            <input type="text" placeholder="Enter a Vendor Name" id="vendor-ac">
            <input type="hidden" value="" id="vendor-ac-id">
            <a href="#" data-toggle="modal" data-target="#invite-vendor-modal">Can't find a vendor? <b>Invite him</b></a>
            <br><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="upload-holder" id="listings"></div>
                </div>
            </div>
            <nav class="navbar navbar-default navbar-fixed-bottom table-control">
                <div class="pull-right">
                    <a href="#" class="disabled">Cancel&nbsp;</a>
                    <button type="button" name="save" data-dump="#listings" class="btn btn-primary">Save</button>
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- end of tasks list --> 
<script type="text/javascript">

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    function fetchSites(vendorid) {
        console.clear();
        changedata = [];

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
                if (parsedData)
                    console.log(parsedData.length);
            }
        });
    }

    $('#listings').handsontable({
        //data: [['','','','']],
        minCols: 20,
        colHeaders: ['SITE CODE', 'MEDIA TYPE', 'CITY', 'LOCALITY', 'NAME', 'WIDTH', 'HEIGHT', 'LIGHTING'],
        rowHeaders: true,
        colWidths: [100, 150, 150, 250, 250, 100, 100, 100],
        manualColumnResize: true,
        manualRowResize: true,
        minSpareRows: 50,
        onChange: function() {
            console.log('onchanhe' + JSON.stringify(arguments));
            if (arguments[0] !== null) {
                changedata[changedata.length] = arguments[0][0][0];
//        changedata.push['asd'];
//        console.log(changedata + ' pushed ' + arguments[0][0][0]);
            } else {
                // console.log('null dda');
            }
//        changedata.push[arguments[0][0][0]];
//        for (var d = 0; d < arguments.length; d++) {
//            changedata.push[arguments[0][0][0]];
//            if (arguments[d] && arguments[d].length > 0) {
//            changedata.push[arguments[d][0][0]];
//            console.log('data - ' + JSON.stringify(changedata) + ' sdf '  + arguments[d][0][0]);
//            //break;
//            }    
//            
//            
//        }    
        },
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
                data: 'width',
                type: 'numeric'
            }, {
                data: 'length1',
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
        //        console.log('change data s' + changedata);
        var cleanData = [];
        var changedata1 = changedata.filter(onlyUnique);
//        console.log('change data s' + changedata1);
        for (var i = 0; i < changedata1.length; i++) {
            var row = data[changedata1[i]];
            if (row.name) {
                cleanData.push({
                    id: row.id,
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
        }

//        data.forEach(function(row){
//            console.log('index' + i);
//            if(row.name) {
//                cleanData.push({
//                    id: row.id,
//                    site_code: row.site_code,
//                    name: row.name,
//                    mediatype: row.mediatype,
//                    locality: row.locality,
//                    city: row.city,
//                    length: row.length1,
//                    width: row.width,
//                    lighting: row.lighting                    
//                });
//            }
//        });
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
        console.log(JSON.stringify(cleanData));

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
                    console.log(data);
                    if (data == true)
                        location.reload();
                    else
                        alert('Failed to save data.')
                },
                error: function(data) { // if error occured
                    alert("Error occured.please try again");
                    alert(data);
                }
            });
        } else {
            alert("Please select Media Vendor from the drop down");
            $(window).scrollTop(0);
            $('#vendor-ac').focus();
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