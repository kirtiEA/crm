<!DOCTYPE html5>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Monitorly</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/application.css">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Task', 'Hours per Day'],
                ['Sites Not Monitored', 11],
                ['Sites With Problems', 2],
                ['Sites With No Problems', 2]
            ]);

            var options = {
                title: '',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>
</head>

<body class="report-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="report-wrap">
                    <h2 class="campaign-name">Pepsi Outdoor Campaign Delhi</h2>

                    <div class="campaign-info">
                        <div class="row">
                            <div class="col-md-3">
                                <ul class="campaign-info-labels">
                                    <li>Sites:</li>
                                    <li>Cities</li>
                                    <li>Campaign Dates:</li>
                                    <li>Generated on:</li>
                                    <li>Type:</span></li>
                                    <li>Vendors:</span></li>
                                </ul>
                            </div>
                            <div class="col-md-9">
                                <ul class="campaign-info-values">
                                    <li><?php echo count($data['sites']); ?></li>
                                    <li>Mumbai (10), Delhi (7), Jaipur (8)</li>
                                    <li><?php echo $data['campaign']['sdate'] . " - " . $data['campaign']['edate']; ?></li>
                                    <li><?php echo $data['campaign']['createdDate']; ?></li>
                                    <li>Proof of Posting</li>
                                    <li>Graphisads, Right Angle Media, Bright Outdoor</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr class="divider">

                    <div class="summary">
                        <h2 class="section-heading">Monitoring Coverage Summary</h2>
                        <div class="summary-diagram">
                            <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
                        </div>

                        <hr class="divider">

                        <div class="tabular-summary">
                            <h2 class="section-heading">Tabular Summary</h2>
                            <table class="table table-bordered">
                                <tr>                                    
                                    <th>Site Name</th>
                                    <th>Location</th>
                                    <th>Media Type</th>
                                    <th>Clicked By</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Photo</th>
                                </tr>
                                <?php foreach($data['sites'] as $site): ?>
                                    <tr>                                    
                                        <td><?php echo $site['sitename']; ?></td>
                                        <td><?php echo $site['location']; ?></td>
                                        <td><?php echo $site['mediatype']; ?></td>
                                        <td><?php //echo $site['clickedby']; ?></td>
                                        <td><?php echo $site['dueDate']; ?></td>
                                        <td><?php echo $site['taskDone']; ?></td>
                                        <td><a href="<?php echo $site['imageName']; ?>">Click to test if link works in pdf</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                            <p class="footnote">*Any footnote lorem ipsum dolorus lorem ipsum dolorus lorem ipsum dolorus lorem ipsum dolorus</p>

                        </div>

                        <hr class="divider">

                        <div class="high-res-images">
                            <h2 class="section-heading">High Resolution Images</h2>
                            <br>
                            <h4>To get high resolution images in zip file click on the button below</h4>
                            <br>
                            <button class="btn btn-primary btn-primary-lg">DOWNLOAD HI-RES IMAGES</button>
                            <br><br>
                            <h4>For low resolution summary photos, see the pages below.</h4>
                        </div>

                        <hr class="divider">

                        <div class="image-gallery">
                            <h2 class="section-heading">Images - <?php echo isset($path) ? $path : '0'; ?></h2>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="image-box">
                                            <div class="status-icon"><span class="glyphicon glyphicon-warning-sign"></span></div>
                                            <div class="image image1"></div>
                                            <div class="color-labels"><span class="legend-icon legend-icon-1"></span><span class="legend-icon legend-icon-2"></span><span class="legend-icon legend-icon-3"></span></div>
                                        </div>
                                        <div class="image-box">
                                            <div class="status-icon"><span class="glyphicon glyphicon-warning-sign"></span></div>
                                            <div class="image image2"></div>
                                            <div class="color-labels"><span class="legend-icon legend-icon-1"></span><span class="legend-icon legend-icon-2"></span><span class="legend-icon legend-icon-3"></span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="image-box">
                                            <div class="status-icon"><span class="glyphicon glyphicon-ok"></span></div>
                                            <div class="image image3"></div>
                                            <div class="color-labels"><span class="legend-icon legend-icon-1"></span><span class="legend-icon legend-icon-2"></span><span class="legend-icon legend-icon-3"></span></div>
                                        </div>
                                        <div class="image-box">
                                            <div class="status-icon"><span class="glyphicon glyphicon-ok"></span></div>
                                            <div class="image image4"></div>
                                            <div class="color-labels"><span class="legend-icon legend-icon-1"></span><span class="legend-icon legend-icon-2"></span><span class="legend-icon legend-icon-3"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




</body>





