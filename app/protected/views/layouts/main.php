<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CRM">
    <meta name="author" content="Kirti">
    <link rel="icon" href="">

    <title>CRM</title>
<div style="display: none" id="completePath"><?php echo Yii::app()->getBaseUrl(true); ?></div>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/jquery-ui.min.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/token-input.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/vendor/bootstrap-multiselect.css" />
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css">
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>


    <!-- Custom styles for this template -->
    <!-- <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" /> -->
            <!-- Placed at the end of the document so the pages load faster -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery-1.10.2.min.js"></script>
        <!-- JavaScript -->

        <!-- JQuery UI CDN -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery-ui.min.js"></script>
        <!-- Latest compiled and minified JavaScript Bootstrap -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/bootstrap.js"></script>
        
        <!-- Token input -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/jquery.tokeninput.js"></script>
        
        <!-- mustache -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/mustache.js"></script>
        
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/vendor/bootstrap-multiselect.js"></script>
        
        <!-- Custom Javascript -->
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
    </head>

    <body >

        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <button type="button" class="btn navbar-toggle collapsed" data-toggle="modal" data-target="#addalead"><span class="fa fa-plus"></span></button>
                    <a class="navbar-brand" href="#">TPM</a>
                </div>
                <div id="navbar" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active crm_menu menu_dash"><a href="<?php echo Yii::app()->request->baseUrl; ?>/dashboard">Dashboard</a></li>
                        <li class="crm_menu menu_contact"><a href="<?php echo Yii::app()->request->baseUrl; ?>/contacts">Contacts</a></li>
                        <li><a href="<?php echo Yii::app()->request->baseUrl; ?>/users">Users</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="navbar-right"><a href="<?php echo Yii::app()->request->baseUrl; ?>/account">Logout</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>

<!-- This is main content area-->
<?php echo $content; ?>


         <!-- Modal one [add a lead] -->
    <div class="modal fade" id="addalead">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span>New Lead</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 col-md-8 col-sm-8 pull-right">
                        <!-- Line 1 starts here -->
                        <div class="form-inline col-md-12 col-xs-12 col-sm-12">
                            <p class="col-md-3 col-sm-3 col-xs-12">Campaign Dates:</p>
                            <div class="col-md-1 hidden-xs">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
                            </div>
                            <input class="col-md-3 col-sm-3 col-xs-12" type="datepicker" id="sdate" name="Start Date" value="" placeholder="Start Date">
                            <div class="col-md-1 hidden-xs">
                                <span class="glyphicon glyphicon-calendar align-right" aria-hidden="true"></span>
                            </div>
                            <input class="col-md-3 col-sm-3 col-xs-12" type="datepicker" id="edate" name="End Date" value="" placeholder="End Date">
                        </div>
                        <!-- line 1 ends here -->
                        <!-- line 2 starts here -->
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="form-group" >
                                <div class="input-group">
                                  <span class="input-group-btn btn-group ">
                                  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">Select Country <span class="caret"></span> </button>
                                  <ul class="dropdown-menu pull-right">
                                    <?php 
                                        $html = '';
                                        $countries = Area::fetchCountryListing();
                                        foreach ($countries as $country) {
                                            $html = $html . '<li>
                                                <input type="radio"  value="' . $country['id'] . '" name="country" checked="checked">
                                                <label for="country">' . $country['name'] . '</label>
                                              </li>';
                                        }
                                        echo $html;
                                    ?>  
                                  </ul>
                                  </span> 
                                  <span class="input-group-btn btn-group ">
                                  <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">USD <span class="caret"></span> </button>
                                  <ul class="dropdown-menu pull-right">
                                      <?php 
                                        $html = '';
                                        $currencies = LookupBaseCurrency::getBaseCurrencyList();
                                        foreach ($currencies as $curr) {
                                            $html = $html . '<li>
                                                <input type="radio"  value="' . $curr['id'] . '" name="curr" checked="checked">
                                                <label for="country">' . $curr['currency_code'] . '</label>
                                              </li>';
                                        }
                                        echo $html;
                                    ?>

                                  </ul>
                                  </span> 
                                  <input type="text" class="form-control" id="appendedInput" name="Budget" placeholder="Budget Proposed">
                                </div>
                            </div>
<!--                            <input class="col-md-3 col-sm-3 col-xs-12" type="text" name="curr" value="" placeholder="Currency">
                            <div class="col-md-1 hidden-xs">
                            </div>
                            <input class="col-md-3 col-sm-3 col-xs-12" type="number" name="Budget" value="" placeholder="Budget Proposed">-->
                        </div>
                        <!-- line 2 ends here -->
                        <!-- line 3 starts here -->
<!--                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <input class="col-md-3 col-sm-3 col-xs-12" type="text" name="curr" value="" placeholder="Currency">
                            <div class="col-md-1 hidden-xs"></div>
                            <input class="col-md-3 col-sm-3 col-xs-12" type="number" name="Budget" value="" placeholder="Budget Proposed">
                            <div class="col-md-1 hidden-xs"></div>
                            <div class="col-md-4 col-xs-12 col-sm-4">
                                <div class="form-group">
                                    <div class="btn-group" role="group" aria-label="...">
                                        <button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addabudget">Add Another Budget</button>
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <!-- line 3 ends here -->
                        <!-- line 4 starts here -->
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <span class="col-md-1 hidden-xs glyphicon glyphicon-tags"></span>
                            <input class="col-md-10 col-xs-12 col-sm-10" type="text" id="tags" name="" value="" placeholder="Media Format Tags">
                        </div>
                        <!-- line 4 ends here -->
                        <!-- line 5 starts here -->
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <textarea class="form-control" rows="3" id="description" placeholder="Description"></textarea>
                        </div>
                        <!-- line 5 ends here -->
                        <!-- Submit Button -->
                        <button type="submit" onclick="createlead();" class="btn btn-success pull-right">Create Lead</button>
                        <!-- /.Submit Button -->
                    </div>
                    <div class="col-xs-12 col-md-4 col-sm-4 pull-left modal-left">
                        <hr class="hidden-sm hidden-md hidden-lg">
                        <h4><b onclick="$('#brand').show();$('#brand_old').empty();" id="brand_old">Brand</b>
                                <input type="text" class="col-md-11 col-sm-11 col-xs-12" placeholder="Brand" style="display: none;" id="brand"> 
                                <input type="text" style="display: none" id="selectedbrandid">
                                <span onclick="$('#brand').show();$('#brand_old').empty();" class="glyphicon glyphicon-edit pull-right">
                                    
                                </span> 
                        </h4>
                        <div class="clearfix"></div>
                        <p><span onclick="$('#agency').show();$('#agency_old').empty();" id="agency_old">Agency</span>
                            <input type="text" class="col-md-11 col-sm-11 col-xs-12" placeholder="Agency" style="display: none;" id="agency">
                            <input type="text" style="display: none" id="selectedagencyid">
                            <span onclick="$('#agency').show();$('#agency_old').empty();" id="agency_old" class="glyphicon glyphicon-edit pull-right"></span> 
                        </p>
                        <div class="clearfix"></div>
                        <p><span onclick="$('#user').show();$('#user_old').empty();" id="user_old"><span class="glyphicon glyphicon-user"></span> Assign To</span>
                            <input type="text" class="col-md-11 col-sm-11 col-xs-12" placeholder="Assign To" style="display: none;" id="user"> 
                            <input type="text" style="display: none" id="selecteduserid">
                            <span onclick="$('#user').show();$('#user_old').empty();" class="glyphicon glyphicon-edit pull-right"></span>
                        </p>
                        <div class="clearfix"></div>
                        <p>Tourism <span class="glyphicon glyphicon-edit pull-right"></span></p>
                        <div class="clearfix"></div>
                        <address style="display: none;" id="address">
                            <strong>Twitter, Inc.</strong><br>
                            795 Folsom Ave, Suite 600<br>
                            San Francisco, CA 94107<br>
                            <abbr title="Phone">P:</abbr> (123) 456-7890
                        </address>

                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal one [add a lead] -->
    <!-- Modal Two -->
    <div class="modal fade " id ="modaldetails">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <span>ID: TPM 123</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 col-md-8 col-sm-8 pull-right">
                        <div class="col-xs-12 text-left">
                            <span class=" col-xs-1 glyphicon glyphicon-tags"></span>
                            <span class=" label label-success">Tag1</span>
                            <span class=" label label-success">Tag2</span>
                            <span class=" label label-success">Tag3</span>
                            <span class="glyphicon glyphicon-edit pull-right"></span>
                        </div>
                        <h5><strong>Singapore</strong> <small><span class="glyphicon glyphicon-edit"></span></small></h5>
                        <h6><strong>SGD</strong> 200,000 <span class="glyphicon glyphicon-edit"></span></h6>
                        <h5><strong>Jakarta</strong> <small><span class="glyphicon glyphicon-edit"></span></small></h5>
                        <h6><strong>IDR</strong> 200,000 <span class="glyphicon glyphicon-edit"></span></h6>
                        <p><mark>Description:</mark>lorem Ipsum Dolor Sit Amet</p>
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <textarea class="form-control" rows="3" placeholder="Remark"></textarea>
                        </div>
                        <input type="submit" name="Add" value="Add" class="btn btn-success">
                        <div class="clearfix"></div>
                        <hr>
                        <div class="col-xs-12 col-md-12 col-sm-12 bottomhalfmodal">
                            <p class="bg-info">Status Change<span class="pull-right">Date</span></p>
                            <p class="bg-info">Status Change<span class="pull-right">Date</span></p>
                            <p class="bg-info">Status Change<span class="pull-right">Date</span></p>
                            <p class="bg-info">Status Change<span class="pull-right">Date</span></p>
                            <p class="bg-info">Status Change<span class="pull-right">Date</span></p>
                        </div>
                        <a href="#" title="">See full history of this lead</a>
                        <input type="submit" name="Add" value="Move To Proposal Sent" class="btn btn-success pull-right">
                    </div>
                    <div class="col-xs-12 col-md-4 col-sm-4 pull-left modal-left">
                        <hr class="hidden-sm hidden-md hidden-lg">
                        <h4><b>Toshiba</b> <span class="glyphicon glyphicon-edit pull-right"></span> </h4>
                        <p>The Media Shop <span class="glyphicon glyphicon-edit pull-right"></span> </p>
                        <u>Campaign Dates</u>
                        <p>Start: 1/1/2015<span class="glyphicon glyphicon-edit pull-right"></span></p>
                        <p>End: 1/1/2015<span class="glyphicon glyphicon-edit pull-right"></span></p>
                        <p><span class="glyphicon glyphicon-user"></span> Thi <span class="glyphicon glyphicon-edit pull-right"></span></p>
                        <p>Tourism <span class="glyphicon glyphicon-edit pull-right"></span></p>
                        <address>
                            <strong>Twitter, Inc.</strong><br>
                            795 Folsom Ave, Suite 600<br>
                            San Francisco, CA 94107<br>
                            <abbr title="Phone">P:</abbr> (123) 456-7890
                        </address>

                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /. Modal Two -->

                <?php if (Yii::app()->user->hasFlash('successconst')) { ?>
        <div id="constantbox">
            <div id="flash-messagesx" class="alert alert-success alert">
                <?php echo Yii::app()->user->getFlash('successconst'); ?>
            </div>
        </div>
        <?php } ?>

        <?php if (Yii::app()->user->hasFlash('success')) { ?>
            <div id="flash-messages" class="alert alert-success alert-dismissible">
                <?php echo Yii::app()->user->getFlash('success'); ?>

            </div>
        <?php } ?>
        <?php if (Yii::app()->user->hasFlash('error')) { ?>
        <div id="flash-messages" class="alert alert-success alert-dismissible" style="background-color: rgb(218, 172, 172); color: black;">
                <?php echo Yii::app()->user->getFlash('error'); ?>

            </div>
        <?php } ?>
        

        
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    </body></html>