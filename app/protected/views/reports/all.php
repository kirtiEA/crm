<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/lightbox_dust.js"></script>
<!-- filters sub-header -->    

<div id="submenu" class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontol" role="form" method="post" id="filter-form">
                <div class="form-group">
                    <h3 class="subheader-heading">Filter Tasks</h3>
                    <div class="control">
                        <label class="control-label">Campaigns</label>
                        <select class="multiselect" id="multiselect-campaigns" multiple="multiple">
                            <?php foreach($campaignIdList as $key => $value) {
                                echo "<option value='$key'>$value</option>";                            
                            } ?>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Assigned To</label>
                        <select class="multiselect" id="multiselect-assignedto" multiple="multiple">
                            <?php foreach($assignedToList as $key => $value) {
                                echo "<option value='$key'>$value</option>";                            
                            } ?>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Start Date</label>
                        <input type="text" class="datepicker" name="sdate" id="srdate" />
                    </div>
                    <div class="control">
                        <label class="control-label">End Date</label>
                        <input type="text" class="datepicker" name="edate" id="erdate" />
                    </div>
                    <input type="hidden" name="campaignids" id="campaignids" />
                    <input type="hidden" name="assignedto" id="assignedto" />
                    <button type="submit" name="filter_submit" id="filter_submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- end of filters sub-header --> 



<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#">Full Campaign</a></li>
    <li><a href="<?php echo Yii::app()->urlManager->createUrl('reports'); ?>">Proof of Posting</a></li>
</ul>

<!-- tasks list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading pull-left">Report</h1>
            <button class="btn btn-secondary table-control pull-right"><span class="glyphicon glyphicon-download"></span> Download Report</button>

    <table class="table table-condensed" style="table-layout:fixed">
        <thead>
            <tr>
                    <th>Campaign</th>
                    <th>Site</th>
                    <th>Location</th>
                    <th>Media Type</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Photo</th>
            </tr>
        </thead>
    </table>

<div class="div-table-content" style="overflow-y:auto;height:400px;margin-top:-22px;">

            <table class="table table-hover" style="table-layout:fixed">
             <tbody>

                <?php
                foreach ($tasks as $t):
                    $trClass = '';
                    if ($t['status'] == 0) {
                        $trClass = 'danger';
                    }
                    ?>
                    <tr class='<?php echo $trClass; ?>' id="<?php echo $t['id']; ?>">
                        <td><?php echo $t['campaign']; ?></td>
                        <td><?php echo $t['site']; ?></td>
                        <td><?php echo $t['location']; ?></td>
                        <td><?php echo $t['mediatype']; ?></td>
                        <td><?php echo strlen($t['assignedto']) ? $t['assignedto'] : 'Unassigned'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($t['duedate'])); ?>
                            <input type="hidden" class="duedate" value="<?php echo date('Y-m-d', strtotime($t['duedate'])); ?>" />
                            <input type="hidden" class="pop" value="<?php echo $t['pop']; ?>" />
                        </td>
                        <td>
                            <?php
                            $status = '';
                            if ($t['status'] == 0) {
                                if($t['duedate'] < date('Y-m-d'))
                                    $status = 'Missed';
                                else 
                                    $status = 'Pending';
                            } else {
                                if ($t['problem']) {
                                    $status = '<span class="glyphicon glyphicon-warning-sign"></span>';
                                } else {
                                    $status = '<span class="glyphicon glyphicon-ok"></span>';
                                }
                            }
                            echo $status;
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($t['status'] == 0) {
                                echo '-';
                            } else {
                                echo '<a href="javascript:void(0);" class="lightbox-btn">View ('.$t['photocount'].')</a>';
                            }
                            ?>
                        </td>
                    </tr>

                <?php endforeach; ?>
                </tbody>

            </table>
                </div>

        </div>
        <div id="img-gallery" style="display:block;"></div>
    </div>
</div>
<!-- end of tasks list --> 
<script type="text/javascript">
$(document).ready(function () {
    $('.standstill').css("table-layout","fixed");


var menu = $('#submenu');
var origOffsetY = menu.offset().top;

var tbheader = $('.standstill');
var tbOffsetY = tbheader.offset().top;


function scroll() {
//    console.log("yes"+tbOffsetY);


    if ($(window).scrollTop() >= origOffsetY) {
        $('#submenu').css("margin-top","0px");
        $('#submenu').addClass('navbar-fixed-top');
       // $('.content').addClass('menu-padding');
               if ($(window).scrollTop() >= tbOffsetY) {
                           console.log("yes"+tbOffsetY);
 //                   $('.standstill').css("margin-top","0px");
                    $('.standstill').css("position","fixed");
                    //$('.standstill').css("top","45px");
                    //$('.standstill').css("width","100%");
                  //  $('.standstill').addClass('navbar-fixed-top');
            }
    } else {
        $('#submenu').css("margin-top","-20px");
        $('#submenu').removeClass('navbar-fixed-top');
        //$('.content').removeClass('menu-padding');
    }


   }

  document.onscroll = scroll;

        $('.mon_menu').each(function() {
            $(this).removeClass('active');
        });
        $('.menu_report').addClass('active');

        $('#filter_submit').click(function(e){
            e.preventDefault();        
            $('#campaignids').val(JSON.stringify($('#multiselect-campaigns').val()));
            $('#assignedto').val(JSON.stringify($('#multiselect-assignedto').val()));
            $('#filter-form').submit();  
          
        });

        $('.lightbox-btn').click(function() {
            console.log('clicked');
            var img_html = '';
            //$(this).parent().append(img_html);
            //$('div#img-gallery a:first-child').ekkoLightbox();
            var duedate = $(this).parents('tr').find('input.duedate').val();//children('td:eq(4).duedate').val();
            var taskid = $(this).parents('tr').attr('id');
            var campaign = $(this).parents('tr').children('td:eq(0)').text();
            var site = $(this).parents('tr').children('td:eq(1)').text();
            var pop = $(this).parents('tr').find('input.pop').val();
            console.log(campaign + " " + site);
            $.ajax({
                url: "<?php echo Yii::app()->urlManager->createUrl('ajax/fetchppimages'); ?>",
                type: "POST",
                data: {                    
                    taskid: taskid,
                    duedate: duedate,
                    pop: pop
                },
                async: false,                
                success: function(data) {
                    //console.log(data);
                    dust.render("lightbox", JSON.parse(data) , function(err, out) {
                        $('#img-gallery').html(out);
                        console.log(err);
                    });
//                    $('#img-gallery').html('');
//                    var img_gal = JSON.parse(data);
//                    //console.log(img_gal);
//                    img_gal.forEach(function(col){
//                        var problems = '';
//                        var installation = (col.installation).split(",");
//                        for (var i = 0; i < installation.length; i++) {
//                            if(installation[i]!=''){
//                                problems += '<span class=\'install\'>'+installation[i]+'</span>';
//                            }
//                        }
//                        var lighting = (col.lighting).split(",");
//                        for (var i = 0; i < lighting.length; i++) {
//                            if(lighting[i]!=''){
//                                problems += '<span class=\'install\'>'+lighting[i]+'</span>';
//                            }
//                        }
//                        var obstruction = (col.obstruction).split(",");                        
//                        for (var i = 0; i < obstruction.length; i++) {
//                            if(obstruction[i]!=''){
//                                problems += '<span class=\'install\'>'+obstruction[i]+'</span>';
//                            }
//                        }
//                        //installation.filter();
//                        //console.log(installation); // + " = " + lighting + " = " + obstruction);
//                        var img_url = '<a href="'+col.imageName+'" data-toggle="lightbox" data-gallery="multiimages" ';
//                        img_url += 'data-title="<div><h2><span>Site: </span>'+site+'</h2><h3><span>Campaign: </span>'+campaign+'</h3><div>'+problems+'</div></div>" ';
//                        img_url += 'data-footer="<div class=\'pull-left\'>Clicked by: '+col.clickedBy+'</div><div class=\'pull-right\'>Clicked at: '+col.clickedDateTime+'</div><div class=\'clearfix\'></div>" ';
//                        img_url += '></a>';
//                        
//                        $('#img-gallery').append(img_url);
//                    });

//$('#img-gallery').append(img_url);
                    $('div#img-gallery a:first-child').ekkoLightbox();
                }
            });

        });
        
        
       var selectedCamp = <?php 
       if (!empty($selectedCampaignIds)) {
           echo "'" .$selectedCampaignIds . "'";
       } else {
           echo "0";
       }    
       ?> ;
       var arrselectedCamp = [];
       if (selectedCamp != 0) {
       arrselectedCamp = selectedCamp.split(',');
       }    
        
       console.log(arrselectedCamp);
    $("#multiselect-campaigns option").each(function()
{
    for(var i=0; i<arrselectedCamp.length; i++) {
        console.log($(this).val() + ' sdfsdfs' + parseInt(arrselectedCamp[i]) );
        if ($(this).val() == parseInt(arrselectedCamp[i])) {
//            $(this).addClass('active');   
            console.log($(this).addClass("active"));
        }   
       }
    // add $(this).val() to your list
    
});   
        
    });
</script>