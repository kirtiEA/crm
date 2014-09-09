<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/listings.js"></script>

<script src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/map.js"></script>
<script>
    $(document).ready(function(){
        
    });
    
    function approvelisting(id) {
        $.ajax({
                   type: 'POST',
                   url: '<?php echo Yii::app()->urlManager->createUrl('ajax/ApproveListingRequest'); ?>',
                   data: {
                       'id': id
                   },
                success:function(data){
                    $('#listing_'+id).remove();
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
    }
</script>        
     <input id="sitetypeid" value="3" style="display: none">   
<div>
<!-- tabs -->    
<ul class="nav nav-tabs" id="sites-tabs" role="tablist" >
    <li ><a href="<?php echo Yii::app()->createUrl('site')?>" >Vendors Sites</a></li>
<!--    <li><a href="" role="tab" data-toggle="tab" >My Sites</a></li>-->
    <li class="active"><a href="#" role="tab" data-toggle="tab" >My Pending Sites</a></li>
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
            
        </div>
        <!-- end of map -->

        <!--list-->
<div class="col-md-4" id="site-list-container" style="height: 700px;" ng-controller = "DemoController">
    <div class="list-container">
                <div class="action-buttons">
                    <button class="btn btn-primary" onclick="location.href='<?php echo Yii::app()->urlManager->createUrl('site/addvendor'); ?>'">Add Vendors Sites</button>
                </div>
<!--                <div class="list-tools">
                    <ul>
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                                    Sort
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                    <li role="presentation" ng-click="order('Price',false)"><a role="menuitem" tabindex="-1" href="#">Price</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Date Added</a></li>
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
                </div>-->
                <div class="list" >
                    <ul id="site-list" >
                        <?php 
                            $html = '';
                            foreach ($lists as $list) {
                                $html = $html . '<li class="list-item" id="listing_'.$list['id'].'">';
                             
                             if(!empty($list['thumbnail'])) {
                                 $html = $html . '<div class="pull-left site-thumb" style="background-image:url(\'' . $list['thumbnail'] . '\')"</div>'; 
                             } else {
                                $html = $html . '<div class="pull-left site-thumb" ></div>';
                             }  
                            
                            $html = $html . '<div class="site-details"><h3>' . $list['name'] .'</h3>'.
                              '<h5><span>' . $list['lighting'] .'</span>' . $list['mediatype'] . '&nbsp;&nbsp;' . $list['width'] . ' x ' . $list['height'] . ' ' . $list['sizeunit'] . '</h5>'
                                . '<h4>' . $list['companyname'] . '</h4></div>'.
                                        
                                '<div  class="pull-right list-item-buttons">
                                <div  class="btn btn-secondary" onclick="approvelisting(\'' . $list['id'] .'\')">Approve</div>
                            </div>
                            <div class="clearfix"></div>';
                                echo $html;
                            }
                        ?>

                        </li>
                    </ul>
                </div>
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