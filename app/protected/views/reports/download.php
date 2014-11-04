<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reports/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reports/main.css">
    </head>
    <body>
        <nav class="navbar navbar-reportpdf navbar-static-top" role="navigation">
            <div class="container">
                <center>
                    <?php echo $data['campaign']['name']; ?>
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
                                    <td><?php echo date('d/m/Y', strtotime($site['dueDate'])); ?></td>
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

            <div class="row">
                <div class="col-md-12">
                    <center><h3>Images</h3></center>
                </div>
            </div>

            <div class="row photo">
                <div class="col-md-6 sitepic">
                    <center><b>Site Name:</b> Vodafone-Delhi <br>
                        <b>Clicked At:</b> dd/mm/yyyy - hh:mm
                    </center>
                    <center>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/bill1.jpg" class="img-thumbnail" >
                    </center>
                    <div class ="problem"><b>Problems: </b>
                        <span class="install">Faded</span>
                    </div>
                </div>
                <div class="col-md-6 sitepic">
                    <center><b>Site Name:</b> Vodafone-Delhi <br>
                        <b>Clicked At:</b> dd/mm/yyyy - hh:mm
                    </center>
                    <center>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/bill2.jpg"  class="img-thumbnail">
                    </center>
                    <div class ="problem"><b>Problems: </b>
                        <span class="lighting">Partially Unlit</span>
                    </div>
                </div>
            </div>
            <div class="row photo">
                <div class="col-md-6 sitepic">
                    <center><b>Site Name:</b> Vodafone-Delhi <br>
                        <b>Clicked At:</b> dd/mm/yyyy - hh:mm
                    </center>
                    <center>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/bill3.jpg" class="img-thumbnail">
                    </center>
                    <div class ="problem"><b>Problems: </b>
                        <span class="lighting">Partially Unlit</span>
                    </div>
                </div>
                <div class="col-md-6 sitepic">
                    <center><b>Site Name:</b> Vodafone-Delhi <br>
                        <b>Clicked At:</b> dd/mm/yyyy - hh:mm
                    </center>
                    <center>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/bill4.jpg"  class="img-thumbnail">
                    </center>
                    <div class ="problem"><b>Problems: </b>
                        <span class="lighting">Partially Unlit</span>
                    </div>
                </div>
            </div>
            <div class="row photo">
                <div class="col-md-6 sitepic">
                    <center><b>Site Name:</b> Vodafone-Delhi <br>
                        <b>Clicked At:</b> dd/mm/yyyy - hh:mm
                    </center>
                    <center>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/bill5.jpg" class="img-thumbnail">
                    </center>
                    <div class ="problem"><b>Problems: </b>
                        <span class="install">Faded</span>
                    </div>
                </div>
                <div class="col-md-6 sitepic">
                    <center><b>Site Name:</b> Vodafone-Delhi <br>
                        <b>Clicked At:</b> dd/mm/yyyy - hh:mm
                    </center>
                    <center>
                        <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/bill6.jpg"  class="img-thumbnail">
                    </center>
                    <div class ="problem"><b>Problems: </b>
                        <span class="lighting">Partially Unlit</span>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </body>
</html>
