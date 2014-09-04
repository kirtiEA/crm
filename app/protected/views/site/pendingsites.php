<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/listings.js"></script>

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/map.js"></script>
<script>
    $(document).ready(function(){
        
    });
</script>        
        
<div>
<!-- tabs -->    
<ul class="nav nav-tabs" id="sites-tabs" role="tablist" >
    <li class="active"><a href="#" role="tab" data-toggle="tab">Vendors Sites</a></li>
    <li><a href="#" role="tab" data-toggle="tab" >My Sites</a></li>
    <li><a href="sites_mypendingsites.html" role="tab" data-toggle="tab" >My Pending Sites</a></li>
</ul>

<!-- end of tabs --> 

<!--map and list -->  

<div class="container-fluid content-wrapper map-view" >
    <div class="row"> 

        <!-- map -->
        <div class="col-md-8" id="map" style="height: 700px;">
<!--            <div id="clearoption" style="display:none" ><img src="<?php //echo Yii::app()->getBaseUrl();?> + /images/vendorProfileDemo/x.gif">Clear Slection</div>-->
      
<!--      <nav id="social-sidebar">

        <ul>

          <li>
            <a href="#" class="entypo-drawing">
              <span>Drawing Tool</span>
            </a>
          </li>

          <li>
            <a href="#" class="entypo-audience" >
              <span>Audience View</span>
            </a>
          </li>

          <li>
            <a href="#" class="entypo-visulization" >
              <span>Visulization</span>
            </a>
          </li>

        </ul>

      </nav>-->
      
            <div class="map-container" id="map_canvas" style="height: 700px;"></div>
            
            <!-- detailed-view -->
            <div id="detailed-view" ng-show="visible" ng-controller="DetailController">
              <div class="detailed-view-container">
                <h2 class="semi-bold">{{pulldata.Name}}<span class="glyphicon glyphicon-remove pull-right" ng-click="visible=false"></span></h2>
                
                <!-- carousel -->
                <div class="carousel slide" id="detailed-view-carousel" data-interval="2000" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#detailed-view-carousel" data-slide-to="0" class="active"></li>
    <li data-target="#detailed-view-carousel" data-slide-to="1"></li>
    <li data-target="#detailed-view-carousel" data-slide-to="2"></li>
  </ol>

  <!-- Controls -->
  <a class="left carousel-control" href="#detailed-view-carousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#detailed-view-carousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>
            <!-- Price and add to plan -->
            <div class="row">
                <div class="col-md-6"><h2 class="semi-bold">{{pulldata.Price}}</h2></div>
                <div class="col-md-2"><div class="glyphicon glyphicon-star-empty star-empty-custom"></div>
                </div>
                <div class="col-md-4">
                    <div class="pull-right">
                        <button class="btn btn-primary-custom2"><span class="glyphicon glyphicon-plus"></span>&nbsp; Add to Plan
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- table -->          
            <table class="table table-custom">
                <tr>
                    <td class="glyphicon glyphicon-picture glyphicon-custom"></td>
                    <td><h4 class="semi-bold">Media Type</h4></td>
                    <td><h4>{{pulldata.MediaType}}</h4></td>
                </tr>
                <tr>
                    <td></td>
                    <td><h4 class="semi-bold">Lighting</h4></td>
                    <td><h4>{{pulldata.Lighting}}</h4></td>
                </tr>
                <tr>
                    <td></td>
                    <td><h4 class="semi-bold">Width (ft)</h4></td>
                    <td><h4>{{pulldata.Width}} ft</h4></td>
                </tr>
                <tr>
                    <td></td>
                    <td><h4 class="semi-bold">Height (ft)</h4></td>
                    <td><h4>{{pulldata.Height}} ft</h4></td>
                </tr>
                <tr>
                    <td class="glyphicon glyphicon-picture glyphicon-custom"></td>
                    <td><h4 class="semi-bold">Availability</h4></td>
                    <td><h4>Contact Vendor</h4></td>
                </tr>
                <tr>
                    <td class="glyphicon glyphicon-picture glyphicon-custom"></td>
                    <td><h4 class="semi-bold">Address</h4></td>
                    <td><h4> {{pulldata.Address}}</h4></td>
                </tr>
                <tr>
                    <td class="glyphicon glyphicon-picture glyphicon-custom"></td>
                    <td><h4 class="semi-bold">Lat, Long</h4></td>
                    <td><h4>{{pulldata.Lat}}, {{pulldata.Lng}}</h4></td>
                </tr>
            </table>
    
              </div>
            </div>
        </div>
        <!-- end of map -->

        <!--list-->
<div class="col-md-4" id="site-list-container" style="height: 700px;" ng-controller = "DemoController">
    <div class="list-container">
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="location.href='<?php echo Yii::app()->urlManager->createUrl('site/addvendor'); ?>'">Add Vendors Sites</button>
                </div>
                <div class="list-tools">
                    <ul>
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                    Sort
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                    <li role="presentation" ng-click="order('Price',false)"><a role="menuitem" tabindex="-1" href="#">Price</a></li>
<!--                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Date Added</a></li>-->
                                    <li role="presentation" ng-click="order('id',false)"><a role="menuitem" tabindex="-1" href="#">Popularity</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <input type="text" placeholder="Search" ng-model="search">
                        </li>
                        <li class="map-view-icon active pull-right"></li>
                        <li class="spreadsheet-view-icon pull-right"></li>
                    </ul>
                </div>
                <div class="list" >
                    <ul id="site-list" infinite-scroll='reddit.nextPage(2)' infinite-scroll-disabled='reddit.busy' infinite-scroll-distance='9'>
                        <li class="list-item" data-ng-repeat="banner in reddit.products | filter:search" ng-animate="'animate'">
                            <div class="pull-left site-thumb" style="background-image: {{banner.thumbnail}}"></div>
                            <div class="site-details">
                                <h3>{{banner.name}}</h3>
                                <h5><span>Non-lit </span>{{banner.type}} &nbsp; &nbsp; {{banner.width}} x {{banner.height}} ft</span></h5>
                                <h4>Pioneer</h4>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </div>
            </div>  
     <div ng-show='reddit.busy'>
        <center><img src="images/loader.gif"> </center>                 
</div>
            
                
          </div>
       </div>
        <!-- end of list-->

    </div> 

</div>
</div>
<!-- end of map and list -->    

<script>

        $('.mon_menu').each(function() {
            $(this).removeClass('active');            
        });
        $('.menu_site').addClass('active');

</script>