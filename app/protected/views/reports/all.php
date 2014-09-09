<!-- filters sub-header -->    

<div class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontol" role="form" method="post">
                <div class="form-group">
                    <h3 class="subheader-heading">Filter Tasks</h3>
                    <div class="control">
                        <label class="control-label">Campaigns</label>
                        <select class="multiselect" id="multiselect-campaigns" name="campaign" multiple="multiple">
                            <option value="9">Airtel (9)</option>
                            <option value="23">Vodafone (23)</option>
                            <option value="21">Volkswagen (21)</option>
                            <option value="4">Jockey (4)</option>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Assigned To</label>
                        <select class="multiselect" id="multiselect-assignedto" name="assignedto" multiple="multiple">
                            <option value="9">Shyam (9)</option>
                            <option value="23">Ram (23)</option>
                            <option value="21">Mohan (21)</option>
                            <option value="4">Ramesh (4)</option>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Start Date</label>
                        <input type="text" class="datepicker" name="sdate" id="scdate" />
                    </div>
                    <div class="control">
                        <label class="control-label">End Date</label>
                        <input type="text" class="datepicker" name="edate" id="ecdate" />
                    </div>
                    <button type="submit" name="filter_submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of filters sub-header --> 

<ul class="nav nav-tabs" role="tablist">
    <li><a href="<?php echo Yii::app()->urlManager->createUrl('reports'); ?>">Proof of Posting</a></li>
    <li class="active"><a href="#">Full Campaign</a></li>
</ul>

<!-- tasks list --> 
<div class="container-fluid content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="list-heading pull-left">Report</h1>
            <button class="btn btn-secondary table-control pull-right"><span class="glyphicon glyphicon-download"></span> Download Report</button>
            <table class="table table-hover">
                <tr>
                    <th>Campaign</th>
                    <th>Site</th>
                    <th>Media Type</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Photo</th>
                </tr>

                <?php
                foreach ($tasks as $t):
                    $trClass = '';
                    if ($t['status'] == 0) {
                        $trClass = 'danger';
                    }
                    ?>
                    <tr class='<?php echo $trClass; ?>' id="<?php echo $t['id'] ?>">
                        <td><?php echo $t['campaign']; ?></td>
                        <td><?php echo $t['site']; ?></td>
                        <td><?php echo $t['mediatype']; ?></td>
                        <td><?php echo $t['assignedto']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($t['duedate'])); ?><input type="hidden" class="duedate" value="<?php echo date('Y-m-d', strtotime($t['duedate'])); ?>" /></td>
                        <td>
                            <?php
                            $status = '';
                            if ($t['status'] == 0) {
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
                                echo '<a href="#" class="lightbox-btn">View</a>';
                            }
                            ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>
        </div>
        <div id="img-gallery" style="display:block;"></div>
    </div>
</div>
<!-- end of tasks list --> 
<script type="text/javascript">
    $(function() {
        $('.mon_menu').each(function() {
            $(this).removeClass('active');
        });
        $('.menu_report').addClass('active');

        $('.lightbox-btn').click(function() {
            console.log('clicked');
            var img_html = '';
            //$(this).parent().append(img_html);
            //$('div#img-gallery a:first-child').ekkoLightbox();
            var duedate = $(this).parents('tr').find('input.duedate').val();//children('td:eq(4).duedate').val();
            var taskid = $(this).parents('tr').attr('id');
            $.ajax({
                url: "<?php echo Yii::app()->urlManager->createUrl('ajax/fetchppimages'); ?>",
                type: "POST",
                data: {                    
                    taskid: taskid,
                    duedate: duedate
                },
                async: false,                
                success: function(data) {
                    $('#img-gallery').html('');
                    var img_gal = JSON.parse(data);
                    console.log(img_gal);
                    img_gal.forEach(function(col){
                        var img_url = '<a href="'+col.imageName+'" data-toggle="lightbox" data-gallery="multiimages"></a>';
                        console.log(img_url);
                        $('#img-gallery').append(img_url);
                    });
                    $('div#img-gallery a:first-child').ekkoLightbox();
                }
            });

        });
    });
</script>