<!-- Container -->
    <div class="container form-top-container">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-12">
                <!-- On larger screens and tablets -->
                <form class="form-inline col-xs-12 col-md-12 col-lg-12 col-sm-12 text-center" id="form-top-container">
                    <div class="form-group">
                        <!-- Split button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default button-name" id="salesPersonDropdown">Sales Person</button>
                            <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                
                                <?php 
                                $html = '';
                                $brandauto = array();
                                foreach ($sales as $user) {
                                    $temp = array(
                                        'value' => $user['id'],
                                        'label' => $user['name']
                                    );
                                    array_push($brandauto, $temp);
                                    $html = $html .  "<li><a>" . $user['name'] . "</a></li>" ;
                                }
                                $html = $html . '<li id="allsales" style="display: none;">' . json_encode($brandauto) . '</li>';
                                echo $html;
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- Split button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default button-name">Brands</button>
                            <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <?php 
                                $html = '';
                                $brandauto = array();
                                foreach ($brands as $brand) {
                                    $value_name = $brand->name;
                                    $html = $html .  "<li><a>" . $brand->name . "</a></li>" ;
                                    $temp = array(
                                        'value' => $brand->id,
                                        'label' => $brand->name
                                    );
                                    array_push($brandauto, $temp);
                                }
                                $html = $html . '<li id="allbrands" style="display: none;">' . json_encode($brandauto) . '</li>';
                                echo $html;
                                ?>
                            </ul>
                        </div>
                    </div>
                    <!-- Remove this block if categories not needed -->
                    <div class="form-group">
                        <select class="multiselect" id="multiselect-campaigns" multiple="multiple" >
                            <option><a href="#">Auto Lubricant</a></option>
                                <option><a href="#">Auto Tyre</a></option>
                                <option><a href="#">Automotive</a></option>
                                <option><a href="#">Health - Board</a></option>
                                <option><a href="#">Health - Hospitals</a></option>
                                <option><a href="#">Health - Others</a></option>
                                <option><a href="#">Tourism - Hotel & Resorts / Airoptionnes</a></option>
                                <option><a href="#">Tourism - Board</a></option>
                                <option><a href="#">Tourism - Others</a></option>
                                <option><a href="#">Telco</a></option>
                                <option><a href="#">Property - Developer</a></option>
                                <option><a href="#">Retail</a></option>
                                <option><a href="#">Electronics / IT</a></option>
                                <option><a href="#">F&B</a></option>
                                <option><a href="#">E Commerce</a></option>
                        </select>
                        <!-- Split button -->
<!--                        <div class="btn-group">
                            <button type="button" class="btn btn-default ">Categories</button>
                            <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Auto Lubricant</a></li>
                                <li><a href="#">Auto Tyre</a></li>
                                <li><a href="#">Automotive</a></li>
                                <li><a href="#">Health - Board</a></li>
                                <li><a href="#">Health - Hospitals</a></li>
                                <li><a href="#">Health - Others</a></li>
                                <li><a href="#">Tourism - Hotel & Resorts / Airlines</a></li>
                                <li><a href="#">Tourism - Board</a></li>
                                <li><a href="#">Tourism - Others</a></li>
                                <li><a href="#">Telco</a></li>
                                <li><a href="#">Property - Developer</a></li>
                                <li><a href="#">Retail</a></li>
                                <li><a href="#">Electronics / IT</a></li>
                                <li><a href="#">F&B</a></li>
                                <li><a href="#">E Commerce</a></li>
                            </ul>
                        </div>-->
                    </div>
                    <!-- Remove this if categories not needed -->
                    <div class="form-group">
                        <!-- Split button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-default button-name">Tags</button>
                            <button type="button" class="btn btn-default dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Digital Outdoor</a></li>
                                <li><a href="#">Creative Outdoor</a></li>
                                <li><a href="#">Symbolic</a></li>
                                <li><a href="#">Point Of Sales</a></li>
                                <li><a href="#">Network</a></li>
                                <li><a href="#">Airport</a></li>
                                <li><a href="#">Roadshows</a></li>
                                <li><a href="#">Malls</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="form-group hidden-xs">
                        <input type="search" class="form-control" placeholder="Search">
                    </div>
                    <button type="submit" class="btn btn-primary hidden-xs">Clear</button>
                    <div class="form-group hidden-xs">
                        <div class="btn-group" role="group" aria-label="">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addalead"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#addalead">Add A Lead</button>
                        </div>
                    </div>

                </form>
                <!-- /.On larger screens and tablets -->
            </div>
        </div>

    </div>
    <!-- /.container -->
    <div class="clearfix"></div>
    <!-- container two -->
    <div class="container-fluid data-content">
        <!-- row starts here -->
        <div class="row data-content-row">
            <?php
            $html = '';
            foreach ($buckets as $bucket) {
                $html = $html . '<div class="col-xs-12 col-md-3 col-sm-3 col-lg-3">
                <!-- Panel first starts -->
                <div class="panel panel-default draggable">
                    
                    <div class="panel-heading">
                        <b>' . $bucket['name'] .'</b>
                        <b><span class="fa fa-caret-square-o-down pull-right" data-toggle="modal" data-target="#modaldetails"></span></b>
                    </div>
                    <div class="panel-body"  >
                        <ul class="list-group draggable-first bucket" id="bucket_'. $bucket['id'] .'" onload="loadleads('. $bucket['id'] .');">
                        </ul>
                    </div>
                </div>
                </div>';            
            }
            echo $html;
            ?>
            
            
<!--            <div class="col-xs-12 col-md-3 col-sm-3 col-lg-3">
                 Panel first starts 
                <div class="panel panel-default draggable">
                     Default panel contents 
                    <div class="panel-heading">
                        <b>Lead(10%)</b>
                        <b><span class="fa fa-caret-square-o-down pull-right" data-toggle="modal" data-target="#modaldetails"></span></b>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group draggable-first">
                             list-item one starts 
                            <li class="list-group-item list-group-item-default draggable">
                                <div>
                                    <h4>Amy</h4>
                                    <p>Budget: SGD 10,000</p>
                                    <p>Campaign Dates: 1/1/2015-1/6/2015</p>
                                    <p>
                                        <span class="pull-left"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><b>Matthew</b></span>
                                        <span class="pull-right">Last Updated:1/12/2014</span>
                                    </p>
                                </div>
                            </li>
                             List item one ends 
                            <div class="clearfix"></div>
                             list-item one starts 
                            <li class="list-group-item list-group-item-default draggable bg-success">
                                <div>
                                    <h4>Ana</h4>
                                    <p>Budget: SGD 10,000</p>
                                    <p>Campaign Dates: 1/1/2015-1/6/2015</p>
                                    <p>
                                        <span class="pull-left"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><b>Matthew</b></span>
                                        <span class="pull-right">Last Updated:1/12/2014</span>
                                    </p>
                                </div>
                            </li>
                             List item one ends 
                            <div class="clearfix"></div>
                             list-item one starts 
                            <li class="list-group-item list-group-item-default draggable">
                                <div>
                                    <h4>Dave</h4>
                                    <p>Budget: SGD 10,000</p>
                                    <p>Campaign Dates: 1/1/2015-1/6/2015</p>
                                    <p>
                                        <span class="pull-left"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><b>Matthew</b></span>
                                        <span class="pull-right">Last Updated:1/12/2014</span>
                                    </p>

                                </div>
                            </li>
                             List item one ends 
                            <div class="clearfix"></div>
                            <li class="list-group-item list-group-item-default draggable">
                                <div>
                                    <h4>Dyson</h4>
                                    <p>Budget: SGD 10,000</p>
                                    <p>Campaign Dates: 1/1/2015-1/6/2015</p>
                                    <p>
                                        <span class="pull-left"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><b>Matthew</b></span>
                                        <span class="pull-right">Last Updated:1/12/2014</span>
                                    </p>

                                </div>
                            </li>
                        </ul>
                        <p class="addContent" data-toggle="modal" data-target="#modaldetails">Add A Card...</p>
                    </div>

                </div>
                 Panel first ends 
            </div>-->
        </div>
        <!-- row ends here -->
    </div>
    <!-- /.container two -->
    <script>

    </script>    
    
    <script id="card">
        {{#.}}
            <li class="list-group-item list-group-item-default draggable" id="card_{{id}}">
                <div>
                    <h4>Brand:{{brand}}</h4>
                    <p>Budget: {{budget}}</p>
                    <p>Campaign Dates: {{campaignstartdate}}-{{campaignenddate}}</p>
                    <p>
                        <span class="pull-left"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><b>{{user}}</b></span>
                        <span class="pull-right">Last Updated:{{lastupdated}}</span>
                    </p>

                </div>
            </li>
        <div class="clearfix"></div>
{{/.}}
    </script>    
    
    <script>
        $('.bucket').each(function() {
           var id = this.id.split('_');
           //alert(id[1]);
           loadleads(id[1]);
        });
    </script>