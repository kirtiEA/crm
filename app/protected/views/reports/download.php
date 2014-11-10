<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
</head>

<body style="text-align: center;">


    <nav class="navbar navbar-reportpdf navbar-static-top" role="navigation">
      <div><h3><small><?php echo $data['campaign']['name']; ?></small></h3>  </div>
</nav>


<br>
<div class="container-fluid content-wrapper">

  <!-- table info --> 

  <div class="row">
    <div class="col-md-12">

      <table class="table table-striped">
          <tr>
              <td class="col-md-3"><b>Sites:</b></td>
              <td class="col-md-9"> 
                  <?php
                    $siteCities = '';
                    $siteCityCount = 0;
                    foreach ($data['sitesInCities'] as $key => $value) {
                        $siteCities .= $key . ' (' . $value . '), ';
                        $siteCityCount += $value;
                    }
                    echo $siteCityCount;
                    ?>
              </td>
          </tr>
          <tr>
              <td class="col-md-3"><b>Cities:</b></td>
              <td class="col-md-9"><?php echo $siteCities; ?></td>
          </tr>
          <tr>
              <td class="col-md-3"><b>Camping Dates:</b></td>
              <td class="col-md-9"><?php echo $data['campaign']['sdate'] . " - " . $data['campaign']['edate']; ?></td>
          </tr>
          <tr>
              <td class="col-md-3"><b>Generated on:</b></td>
              <td class="col-md-9"><?php echo date('Y-m-d h:i:s'); ?></td>
          </tr>

      </table>
  </div>
</div>
<hr>
<!--  EDN table info --> 


<!-- tasks list --> 

<div class="row">
    <div class="well col-md-12">
        <center><h3 class="list-heading">Report</h3></center>
        <table class="table table-hover">
            <tbody><tr class="table-hover-tr">
                <th>Site</th>
                <th>Location</th>
                <th>Media Type</th>
                <th>Clicked By</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Photo</th>
            </tr>


<?php foreach ($data['sites'] as $site): ?>
            <tr class="danger" id="927">
                <td><?php echo $site['sitename']; ?></td>
                <td><?php echo $site['location']; ?></td>
                <td><?php echo $site['mediatype']; ?></td>
                <td>Unassigned</td>
                <td><?php echo date('d/m/Y', strtotime($site['dueDate'])); ?>
                </td>
                <td>
                    <?php
                                        $status = '';
                                        if ($site['status'] == 0) {
                                            if ($site['dueDate'] < date('Y-m-d')) {
                                                $status = 'Missed';
                                            } else {
                                                $status = 'Pending';
                                            }
                                        } else {
                                            if ($site['problem']) {
                                                $status = 'Warning'; //'<span class="glyphicon glyphicon-warning-sign"></span>';
                                            } else {
                                                $status = 'OK'; //'<span class="glyphicon glyphicon-ok"></span>';
                                            }
                                        }
                                        echo $status;
                                        ?>
                </td>
                <td>
                    <?php
                                        if ($site['status'] == 0) {
                                            echo '-';
                                        } else {
                                            echo '<a href="javascript:void(0);" class="lightbox-btn">View (' . $site['photocount'] . ')</a>';
                                        }
                                        ?>                        
                </td>
                </tr>
                <?php endforeach; ?>


                                    </tbody></table>
                                </div>
                            </div>
                            <hr>
                            <!-- end of tasks list --> 
<div class="clearfix"></div>
                            <div class="row">
                                <div class="col-md-12">
                                  <center>
                                    <h3>High Resolution Images</h3>
                                    <p>To get high resolution images in zip file click on the button below</p>
                                    
                                    <div class="heading-image-download">
                                
                                    <div class="heading-image-download-button">
                                    <b>DOWNLOAD HI-RES IMAGES</b>
                                    </div>
                                    </div>
                                    
                                    <br>
                                    <p>For low resolution summary photos, see the pages below.</p>

                                </center>
                            </div>
                        </div>
                        <hr>
    <div class="clearfix"></div><br/>

                        <div class="row">
                            <div class="full-row">
                                <h3 class="full-row-h3"><b class="full-row-h3-b">Images</b></h3><br>
                            </div>
                        </div> 
                        <br/>
                    <div class="photos">
                        <?php
                    foreach ($data['photos'] as $pictures): ?>
                            <?php $i=0; foreach ($pictures as $pic):?>    
                            <div class="sitepic">

                                <div>
                                  <b>Site Name:</b> <?php echo $pic['name'];?> <br>  
                                </div>
                                <div>
                                  <b>Clicked At:</b> <?php echo date('Y-m-d H:i:s', strtotime($pic['clickedDateTime']));?>
                                </div>
                                 <div class="image_site">
                                    <img src="<?php echo JoyUtilities::getAwsFileUrl('big_' . $pic['imageName'], 'listing'); ?>" class="img-thumbnail" >  
                                 </div>
                                        
                                    

                                <div class ="problem">
                                    <b>Problems: </b>
                                    <span class="lighting">
                                        <?php echo $pic['installation'] . ',' . $pic['lighting'] . ',' . $pic['obstruction']  ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
    <?php endforeach; ?>

                            </div>
    </div>
                        </div>
                    </div>

                    <hr>



                </body>
                </html>