<!-- filters sub-header -->    

<div class="container-fluid sub-header">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontol" role="form">
                <div class="form-group">
                    <h3 class="subheader-heading">Filter Tasks</h3>
                    <div class="control">
                        <label class="control-label">Campaigns</label>
                        <select class="multiselect" id="multiselect-campaigns" multiple="multiple">
                            <option>Airtel (9)</option>
                            <option>Vodafone (23)</option>
                            <option>Volkswagen (21)</option>
                            <option>Jockey (4)</option>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Assigned To</label>
                        <select class="multiselect" id="multiselect-assignedto" multiple="multiple">
                            <option>Shyam (9)</option>
                            <option>Ram (23)</option>
                            <option>Mohan (21)</option>
                            <option>Ramesh (4)</option>
                        </select>
                    </div>
                    <div class="control">
                        <label class="control-label">Start Date</label>
                        <input type="text" class="datepicker" name="sdate" id="sdate" />
                    </div>
                    <div class="control">
                        <label class="control-label">End Date</label>
                        <input type="text" class="datepicker" name="edate" id="edate" />
                    </div>
                    <button class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end of filters sub-header --> 

<ul class="nav nav-tabs" role="tablist">
    <li class="active"><a href="#home" role="tab" data-toggle="tab">Proof of Posting</a></li>
    <li><a href="#profile" role="tab" data-toggle="tab">Full Campaign</a></li>
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
                    <tr class='<?php echo $trClass; ?>'>
                        <td><?php echo $t['campaign']; ?></td>
                        <td><?php echo $t['site']; ?></td>
                        <td><?php echo $t['mediatype']; ?></td>
                        <td><?php echo $t['assignedto']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($t['duedate'])); ?></td>
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
                                echo '<a href="#" id="lightbox-btn">View</a>';
                                echo '<div id="img-gallery">
                        <a href="http://www.inkdots.com/images/Gallery/PGSigns/24.JPG" data-toggle="lightbox" data-gallery="multiimages">
                        </a>
                        <a href="http://4.bp.blogspot.com/_GIchwvJ-aNk/TGxwk2sa4DI/AAAAAAAAT-Q/LLpXyYOQN38/s800/Julia+Roberts+Eat+pray+love+billboard.jpg" data-toggle="lightbox" data-gallery="multiimages">
                        </a>
                    </div>';
                            }
                            ?>
                        </td>
                    </tr>

<?php endforeach; ?>

            </table>
        </div>
    </div>
</div>
<!-- end of tasks list --> 
<script type="text/javascript">
    $(function() {
        $('.mon_menu').each(function() {
            $(this).removeClass('active');
        });
        $('.menu_report').addClass('active');
    });
</script>