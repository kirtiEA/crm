<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>
<div class="row controls clear">
    <div>
        <button class="btn btn-success" id="add">Add new sites </button>
        <button class="btn btn-success" id="manageSites"  onClick="location.href = 'monitorly_managesite.html'">Manage Sites</button>
    </div>
    <div class="add_campaign">
        <form action="" method="post">
            <input type="text" placeholder="Name of the Site" id="newSiteName" name="site-name" value="" class="form-control" />        
            <?php echo CHtml::dropDownList('site-mediatypeid', 'id', $mediaTypes, array('empty' => 'Select Media Type', 'class' => 'form-control')); ?>            
            <input type="text" placeholder="Locality" id="newSiteLocality" name="site-locality" value="" class="form-control" />
            <input type="text" placeholder="Latitude" id="newSiteLat" value="" name="site-lat" class="form-control" style="display:none;" />
            <input type="text" placeholder="Longitude" id="newSiteLng" value="" name="site-lng" class="form-control" style="display:none;" />
            <input type="text" placeholder="Vendor" id="newSiteVendor" value="" name="site-vendor" class="form-control"/>
            <input type="text" placeholder="VendorId" id="newSiteVendorId" name="site-vendorId" value="" class="form-control" style="display:none;" />
            <input type="submit" class="box btn-success" id="newSiteAdd" value="Add" name="site-submit" />
            <a href="#">Cancel</a>
        </form>
    </div>
</div>
<div class="row addons details detailview hr">
    <!--<div class="viewNavigation">
        <ul>
            <li rel="mosaic"><a href="#">Mosaic</a></li>
            <li class="active" rel="map"><a href="#">Map</a></li>

        </ul>
    </div>
    <button class="box btn-success editsite inverse">Edit</button>
    <div class="clear"></div> -->
    <div id="map"></div>

    <div class="item" id="mosaic">
    </div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.geocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script type="text/javascript">
            function initialize() {
                var myLatlng = new google.maps.LatLng(19, 72);
                var mapOptions = {
                    zoom: 4,
                    center: myLatlng
                };
                map = new google.maps.Map(document.getElementById('map'), mapOptions);
            }
            function call_geocomplete() {
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
            }
            $(function() {

                var allVendorsJson = JSON.parse('<?php echo $vendorList; ?>');
                //console.log(allVendorsJson);        
                $('#newSiteVendor').autocomplete({
                    source: allVendorsJson,
                    select: function(event, ui) {
                        $('#newSiteVendor').val(ui.item.value);
                        $('#newSiteVendorId').val(ui.item.id);
                    },
                    change: function(event,ui) {
                        if (ui.item==null) {
                            $("#newSiteVendor").val('');
                            $("#newSiteVendor").focus();
                        }
                    },
                    messages: {
                        noResults: '',
                        results: function() {
                        }
                    },
                });


                $('.mon_menu').each(function() {
                    $(this).removeClass('active');
                });
                $('.menu_site').addClass('active');

                $('#add').click(function(e) {
                    if (!$(this).hasClass('edit')) {
                        $('.add_campaign').show();
                        $(this).addClass('edit');
                    }
                    else {
                        $('.add_campaign').hide();
                        $(this).removeClass('edit');
                    }
                });

                $('.add_campaign a').click(function(e) {
                    $('.add_campaign').hide();
                    $('#add').removeClass('edit');
                });

                initialize();
                call_geocomplete();
            });
</script>