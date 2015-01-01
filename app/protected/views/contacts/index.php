<!-- Handsontables -->
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/vendor/jquery.handsontable.full.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/jquery.handsontable.full.css" />

<div class="container" style="margin-left: 0px;">
    <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
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


<script type="text/javascript">
    $('.crm_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_contact').addClass('active');
    
    var changedata = [];
    var non_empty_text = /^[A-Za-z0-9 ]+$/;
    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }

    function fetchSites() {
        console.clear();
        changedata = [];

        var handsontable = $('#listings').data('handsontable');
        $.ajax({
            url: "<?php echo Yii::app()->urlManager->createUrl('contacts/fetchCompanyContacts'); ?>",
            type: "POST",

            async: false,
            beforeSend: function() {
                // clear the table
                handsontable.loadData(null);
            },
            success: function(data) {
                var parsedData = JSON.parse(data);
                handsontable.loadData(parsedData);
                //if (parsedData)
                    //console.log(parsedData.length);
            }
        });
    }

    $('#listings').handsontable({
        data: <?php echo $contacts;?>,
        minCols: 20,
        colHeaders: ['Company/Brand','Agency' ,'First Name', 'Last Name', 'Phone1', 'Phone2', 'Mobile1', 'Email1', 'Email2', 'Fax', 'Address', 'Website'],
        rowHeaders: true,
        colWidths: [100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100],
        currentRowClassName: 'currentRow',
        currentColClassName: 'currentCol',
        
        manualColumnResize: true,
        manualRowResize: true,
        minSpareRows: 50,
        onChange: function() {
            //console.log('onchange' + JSON.stringify(arguments));
            if (arguments[0] !== null) {
                changedata[changedata.length] = arguments[0][0][0];
            }
        },
        columns: [{
                data: 'brand',
                type: 'text'
            },
            {
                data: 'name',
                type: 'text'
            },
            {
                data: 'fname',
                type: 'text'
            },
            {
                data: 'lname',
                type: 'text'
            },
            {
                data: 'phone1',
                type: 'text'
            },
            {
                data: 'phone2',
                type: 'text'
            },
            {
                data: 'mobile',
                type: 'text'
            },
            {
                data: 'email1',
                type: 'text'
            },
            {
                data: 'email2',
                type: 'text'
            },
            {
                data: 'fax',
                type: 'text'
            },
            {
                data: 'address',
                type: 'text'
            },
            {
                data: 'website',
                type: 'text'
            }]
            
    })  ;


    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function cleanTableData(data) {
        //        console.log('change data s' + changedata);
        var cleanData = [];
        var changedata1 = changedata.filter(onlyUnique);
        //console.log('change data ' + changedata1);
        
        data.forEach(function(row){
            //console.log('index' + i);
            if(!row.id && row.email1 && row.fname) {
                cleanData.push({
                    //id: row.id,
                    name: row.name,
                    brand: row.brand,
                    fname: row.fname,
                    lname: row.lname,
                    phone1: row.phone1,
                    phone2: row.phone2,
                    mobile: row.mobile,
                    email1: row.email1,
                    email2: row.email2,
                    fax: row.fax,
                    address: row.address,
                    website: row.website
                    
                });
            }
        });
        console.log('cleanData : ' + cleanData.length);
        for (var i = 0; i < changedata1.length; i++) {
            var row = data[changedata1[i]];
            if (row.email1 && row.fname ) {
                cleanData.push({
                    id: row.id,
                    name: row.name,
                    brand: row.brand,
                    fname: row.fname,
                    lname: row.lname,
                    phone1: row.phone1,
                    phone2: row.phone2,
                    mobile: row.mobile,
                    email1: row.email1,
                    email2: row.email2,
                    fax: row.fax,
                    address: row.address,
                    website: row.website
                });
            } 
        }
        console.log('cleanData2 : ' + cleanData.length);
        return cleanData;
    }
    $('body').on('click', 'button[name=save]', function() {
        jQuery('#loading-image').show();        
        var handsontableData = $('#listings').data('handsontable').getData();
        var cleanData = cleanTableData(handsontableData);
        //console.log('data of ' + dump, JSON.stringify($container.handsontable('getData')));        

        //console.log(JSON.stringify(handsontableData));
        console.log(JSON.stringify(cleanData));

            //console.log('inside if');
            if(cleanData.length) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->urlManager->createUrl('contacts/massUploadContact'); ?>',
                    data: {
                        'data': JSON.stringify(cleanData)
                    },
                    success: function(data) {
                        //var json = JSON.parse(data);
                        //console.log(data);
                        jQuery('#loading-image').hide();
                        if (data == true)
                      //      location.reload();
                          console.log('asd');
                        else
                            alert('Failed to save data.')
                    },
                    error: function(data) { // if error occured
                        jQuery('#loading-image').hide();
                        //alert("Error occured.please try again");
                        //alert(data);
                        location.reload();
                    }
                });
            } else {
                
            }
        
    });
</script>
<script>
$(document).ready(function() {

        
    fetchSites();
    var hot = $("#listings").handsontable('getInstance');        
    hot.updateSettings({
        readOnly: true
    });
});
</script>