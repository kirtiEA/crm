<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>


    <nav class="navbar navbar-reportpdf navbar-static-top" role="navigation">
      <div class="container">
        <center>
          <h1><small><?php echo $data['campaign']['name']; ?></small></h1>
      </center>
  </div>
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
            <tbody><tr>
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

                                    <button class="btn btn-primary btn-primary-lg" id="signup">DOWNLOAD HI-RES IMAGES</button>
                                    <br>
                                    <p>For low resolution summary photos, see the pages below.</p>

                                </center>
                            </div>
                        </div>
                        <hr>
    <div class="clearfix"></div><br/>

                        <div class="row">
                            <div class="col-md-12">
                                <center><h3>Images</h3><br></center>
                            </div>
                        </div>
<?php
                    foreach ($data['photos'] as $pictures): ?>
                        <div class="row photo">
                            <?php foreach ($pictures as $pic):?>    
                            <div class="col-md-6 col-xs-6 sitepic">
                               <table>
                                <center>
                                <tr><td><b>Site Name:</b> <?php echo $pic['name'];?> <br></td></tr>
                                <tr><td> <b>Clicked At:</b> <?php echo date('Y-m-d', strtotime($pic['clickedDateTime']));?></td></tr>
                                </center>
                                <center>
                                        <tr>
                                            <td>
                                              <img src="<?php echo JoyUtilities::getAwsFileUrl('big_' . $pic['imageName'], 'listing'); ?>" class="img-thumbnail" >  
                                            </td>
                                        </tr>
                                    
                                </center>
                                <div class ="problem">
                                    <tr><td><b>Problems: </b></td></tr>
                                    <tr><td><span class="lighting"><?php echo $pic['installation'] . ',' . $pic['lighting'] . ',' . $pic['obstruction']  ?></span></td></tr>
                                </div>
                                </table>
                            </div>
                            <?php endforeach; ?>
                        </div>
    <div class="clearfix"></div><br>
    <?php endforeach; ?>

                    </div>

                    <hr>



                </body>
                </html>
