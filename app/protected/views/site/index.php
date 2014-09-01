<style>
    .site_marked_fav {
        background-color: #F58707;
        color: white;
        border: none;
    }
     .selectedSite {
                background-color: bisque;
            }
            
            #loading-image {
	background-color: #333;
	width: 100%;
	height: 100%;
	position: fixed;
	top: 0px;
	right: 0px;
	z-index: 9999;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px; /* future proofing */
	-khtml-border-radius: 10px;
        opacity: 0.7;
}
</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-full-2.2.0.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/dust/dust-helpers-1.1.1.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/template/js/listings.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/map.js"></script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<script>
        var icons, map, centerLatLng, legend, circle, mcOptions;
    var userid = 418;
    var companyid;
    var listingIdForPlan =0;
        var centerPointArr = [];
        var allMarkers = [];
        
     var listings = '';   
     var zoomLevel;
     var new_boundary;
    function fetchListings() {
        var chk = [];
        
       // console.log("this is the price slider " + $('.tooltip-inner').text());
        
//        $("input:checkbox[name=type]:checked").each(function()
//        {
//           chk.push($(this).val());
//        });
//        
//        var light = [];
//        $("input:checkbox[name=lighting]:checked").each(function()
//        {
//           light.push($(this).val());
//        });
        var filter = $("#filter_group .active").data("value");
      //  console.log('filter :' + filter + ' light : ' + light + ' mt: ' + chk );
        
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->urlManager->createUrl('ajax/getlisting/'); ?>',
            data:{'type': companyid,
                   'sort':filter,
//                   'userid' : userid
//                   ,
//                   'priceslider':$('.tooltip-inner').text()
                    },
         success:function(data){
             //console.log("data " + data);
                var currList = JSON.parse(data);
                listings += JSON.parse(data);
                var len = JSON.parse(data);
                for (var i =0 ; i < len.length; i++) {
                    listings += len[i];
         //   console.log(listings);
                }    
                dust.render("listings", JSON.parse(data), function(err, out) {
               // console.log("this is test out from ajax11111 : " + out +"err "+ err);    
                $('#site-list').html(out);
                
//                $('#site-list  ').jscroll({
//                    loadingHtml: '<img src="loading.gif" alt="Loading" /> Loading...',
//                    padding: 20,
//                    nextSelector: 'a.jscroll-next:last',
//                    contentSelector: 'li'
//                });

//listings.SiteListing.push(currList);
             //   console.log(listings + " listings length" + JSON.parse(data).SiteListing.length );
                });
                  //$('#site-list  ').jscroll();
                  
                  $('#site-list').scroll(function()
{
    if($('#site-list').scrollTop() == $(document).height() - $('#site-list').height())
    {
        //$('div#loadmoreajaxloader').show();
        console.log('this is is tst');
        $.ajax({
        url: "loadmore.php",
        success: function(html)
        {
            if(html)
            {
                $("#postswrapper").append(html);
                $('div#loadmoreajaxloader').hide();
            }else
            {
                $('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
            }
        }
        });
    }
});
                $(".plus").popover({
                    html : true, 
                    content: function() {
                      return $(".popover-content-custom").html();
                    },
                    title: function() {
                      return $(".popover-title-custom").html();
                    }
                });
                
            },
            error: function(data) { // if error occured
                  alert("Error occured.please try again");
                  alert(data);
             }
           });
    }
</script>    
<!-- tabs -->    
<ul class="nav nav-tabs" id="sites-tabs" role="tablist">
    <li class="active"><a href="sites_vendorssites.html" role="tab" data-toggle="tab">Vendors Sites</a></li>
    <li><a href="#" role="tab" data-toggle="tab">My Sites</a></li>
    <li><a href="sites_mypendingsites.html" role="tab" data-toggle="tab">My Pending Sites</a></li>
</ul>

<!-- end of tabs --> 

<!--map and list -->  

<div class="container-fluid content-wrapper map-view">
    <div class="row"> 

        <!-- map -->
        <div class="col-md-8" id="map">
            <div class="map-container" id="map_canvas">
            </div>
        </div>
        <!-- end of map -->

        <!--list-->
        <div class="col-md-4">
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
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Price</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Date Added</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Popularity</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <input type="text" placeholder="Search">
                        </li>
                        <li class="map-view-icon active pull-right"></li>
                        <li class="spreadsheet-view-icon pull-right"></li>
                    </ul>
                </div>
                <div class="list" >
                    <ul id="site-list">
                        <li class="list-item">
                            <div class="pull-left site-thumb"></div>
                            <div class="site-details">
                                <h3>Kalindi Kunj Road FTC Delhi</h3>
                                <h5><span>Non-lit </span><span>Billboard </span>&nbsp;&nbsp;<span>3.5x5.5 ft2</span></h5>
                                <h4>Pioneer</h4>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-item">
                            <div class="pull-left site-thumb"></div>
                            <div class="site-details">
                                <h3>Kalindi Kunj Road FTC Delhi</h3>
                                <h5><span>Non-lit </span><span>Billboard </span>&nbsp;&nbsp;<span>3.5x5.5 ft2</span></h5>
                                <h4>Bright Outdoors</h4>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-item">
                            <div class="pull-left site-thumb"></div>
                            <div class="site-details">
                                <h3>Kalindi Kunj Road FTC Delhi</h3>
                                <h5><span>Non-lit </span><span>Billboard </span>&nbsp;&nbsp;<span>3.5x5.5 ft2</span></h5>
                                <h4>Pioneer</h4>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                        <li class="list-item">
                            <div class="pull-left site-thumb"></div>
                            <div class="site-details">
                                <h3>Kalindi Kunj Road FTC Delhi</h3>
                                <h5><span>Non-lit </span><span>Billboard </span>&nbsp;&nbsp;<span>3.5x5.5 ft2</span></h5>
                                <h4>Graphisads</h4>
                            </div>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end of list-->

    </div> 

</div>

<!-- end of map and list -->    

<!--<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>-->
<script type="text/javascript">
    function initialize() {
        var myLatlng = new google.maps.LatLng(19, 72);
        var mapOptions = {
            zoom: 4,
            center: myLatlng
        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
    }
    /*function call_geocomplete() {
     $("#newSiteLocality").geocomplete()
     .bind("geocode:result", function(event, result) {
     
     $('#geomsg').html("Result: " + result.formatted_address);
     var lat = parseFloat(result.geometry.location.lat()).toFixed(6);
     var lng = parseFloat(result.geometry.location.lng()).toFixed(6);
     map.fitBounds(result.geometry.viewport);
     $('#newSiteLat').val(lat);
     $('#newSiteLng').val(lng);
     var location = lat + "," + lng;
     //console.log('location - ' + location);
     //console.log($(this).val());
     
     var siteLatlng = new google.maps.LatLng(lat, lng);
     var marker = new google.maps.Marker({
     position: siteLatlng,
     map: map,
     //icon: 'img/marker.png'
     });
     marker.setMap(map);
     marker.setDraggable(true);
     
     google.maps.event.addListener(marker, 'drag', function(event) {
     // Pan to this position (doesn't work!)
     map.panTo(marker.getPosition());
     var c = marker.getPosition();
     lat = (c.lat()).toFixed(6);
     lng = (c.lng()).toFixed(6);
     $('#newSiteLat').val(lat);
     $('#newSiteLng').val(lng);
     });
     })
     .bind("geocode:error", function(event, status) {
     $('#geomsg').html("ERROR: " + status);
     })
     .bind("geocode:multiple", function(event, results) {
     $('#geomsg').html("Multiple: " + results.length + " results found");
     });
     }*/
    $(function() {

        /*var allVendorsJson = JSON.parse('<?php //echo $vendorList; ?>');
        //console.log(allVendorsJson);        
        $('#newSiteVendor').autocomplete({
            source: allVendorsJson,
            select: function(event, ui) {
                $('#newSiteVendor').val(ui.item.value);
                $('#newSiteVendorId').val(ui.item.id);
            },
            change: function(event, ui) {
                if (ui.item == null) {
                    $("#newSiteVendor").val('');
                    $("#newSiteVendor").focus();
                }
            },
            messages: {
                noResults: '',
                results: function() {
                }
            },
        });*/

        $('.mon_menu').each(function() {
            $(this).removeClass('active');            
        });
        $('.menu_site').addClass('active');

        //initialize();
        //call_geocomplete();
    });
</script>