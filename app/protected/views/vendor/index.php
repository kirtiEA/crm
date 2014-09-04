
<!--/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */-->

<!DOCTYPE html5>


<body>

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


    <!-- subheader -->    

    <div class="container-fluid sub-header">
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontol" role="form">
                    <div class="form-group">
                        <h3 class="subheader-heading">Request Approval of Sites</h3>
                        <div class="x control">
                            <label class="control-label">Vendor Name</label>
                            <input type="hidden" value="" class="vendor-ac-id">
                            <input type="hidden" value='<?php echo $id; ?>' class="companyid">
                            <input type="text" class="vendor">
                        </div>
                        <button class="request btn-primary">Request</button>
                        &nbsp;
                        <a href="#" data-toggle="modal" data-target="#invite-vendor-modal">Can't find vendor? <b>Invite him</b></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end of subheader --> 

    <!-- vendor list --> 

    <div class="container-fluid content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <h1 class="list-heading pull-left">Vendors List
                    <?php
                    $no = count($model);
                    echo $no;
                    ?> 
                </h1>
                <button class="btn btn-primary pull-right table-control">Remind All</button>
                <table class="table table-hover" id="vendors-list">

                    <?php foreach ($model as $value): ?>
                        <tr>
                            <td><?php echo $value['name']; ?></td>
                            <td><?php echo $value['vendoradmin']; ?></td>
                            <td>Invited on <?php echo $value['createddate']; ?></td>
                            <td><?php
                                $html = "";
                                if ($value['accepteddate'] !== NULL) {
                                    $html = $html . '<span class="glyphicon glyphicon-ok"></span>Accepted';
                                } else
                                    $html = $html . '<span class="glyphicon glyphicon-warning-sign"></span> Pending';
                                echo $html;
                                ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
<!--                    <tr>
                <td>Pioneer</td>
                <td>admin@pioneer.com</td>
                <td>Invited on 12/09/2014</td>
                <td><span class="glyphicon glyphicon-ok"></span> Accepted</td>
                <td></td>
            </tr>
            <tr>
                <td>Bright Outdoor</td>
                <td>admin@brightoutdoor.com</td>
                <td>Invited on 12/09/2014</td>
                <td><span class="glyphicon glyphicon-warning-sign"></span> Pending</td>
                <td><button class="btn btn-secondary">Remind</button>
                </td>
            </tr>-->
                </table>
            </div>
        </div>
    </div>

    <!-- end of vendor list --> 


</body>


<script type="text/javascript">
    $(function() {

        //autocomplete for company name in vendor subscription form
        var allVendorJson = JSON.parse('<?php echo $vendorList; ?>');
        //console.log($id);
        $('.vendor').autocomplete({
            source: allVendorJson,
            select: function(event, ui) {
                console.log(ui.item.value + ', ' + ui.item.id);
                $(".vendor-ac-id").val(ui.item.id);

            },
            change: function(event, ui) {
                if (ui.item == null) {
                    $(".vendor").val('');
                    $(".vendor-ac-id").val('');
                    $(".vendor").focus();
                }
            },
            messages: {
                noResults: '',
                results: function() {
                }
            },
        })
    });

    //request vendor
    $('.request').click(function() {
        var vendorid = $(this).siblings('.x').children('.vendor-ac-id').val();
        var companyid = $(this).siblings('.x').children('.companyid').val();
        console.log('shruti:' + vendorid + ' , ' + companyid);
        $.ajax({
            type: 'POST',
            url: $('#completePath').text() + '/ajax/RequestedVendor',
            data: {'vendorid': vendorid,
                'companyid': companyid,
            },
            success: function(data) {
                alert("Request sent successfully");
            }
        });
    });

</script>

