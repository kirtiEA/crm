    var fJS;
    function fetchCampaigns(type){
        $.ajax({
                   type: 'POST',
                   url: $('#completePath').text() +  '/ajax/fetchCampaigns',
                   data: {
                       'type': type
                   },
                success:function(data){
dust.render("campaigns", JSON.parse(data) , function(err, out) {
            $('#campaigns').html(out);
            var cnt = JSON.parse(data);
            //console.log(cnt.length + " sdfsfsdfsfsddsfdsfsd") ;
            $('.cnt'+type).html('(' + cnt.length + ')');
            $('.cnt').html(cnt.length);
            console.log(err);
            //expand collapse content  
            $('.clickfor-show-hide').click(function(e) {
//                $(this).toggle();

                $(this).siblings('.show-hide-content').each(function() {
                    $(this).toggle();
                });

                //switch plus minus icons
                if ($(this).find('span').hasClass('glyphicon-plus')) {
                    $(this).find('span').removeClass('glyphicon-plus').addClass('glyphicon-minus');
                }
                else {
                    $(this).find('span').removeClass('glyphicon-minus').addClass('glyphicon-plus');
                }

            });
        });
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
         
    }
    
    function fetchCampaignDetails(id) {
        $.ajax({
                   type: 'POST',
                   url: $('#completePath').text() +  '/ajax/campaignDetails',
                   data: {
                       'cid': id
                   },
                success:function(data){
                    $('#add-site-modal').modal('hide');
                    $('.selectedCampaignId').html('');
                    $('#campaignListings').html('');
                     dust.render("addedListingsToCampaign", JSON.parse(data), function(err, out) {
                    $('#campaign_' + id).html(out);
                        console.log(err);
                    });    
//                                  $(this).siblings('.show-hide-content').each(function() {
//                    $(this).toggle();
//                });
                    //console.log(data);  
                   },
                   error: function(data) { // if error occured
                         alert("Error occured.please try again");
                         alert(data);
                    }
                  });
    }
        function onlyUnique(value, index, self) { 
            return self.indexOf(value) === index;
        }
    var addtocampaign = [];
    var removefromcampaign = [];

    function addToCampaign(id) {
        addtocampaign.push(id);
        $('#fjs_listing_' + id).addClass('selected');
        $('#fjs_listing_' + id + ' span').removeClass('glyphicon-plus').addClass('glyphicon-remove').attr('onclick', 'removeFromArrayAddToCampaign(\'' + id + '\')');
        var campid = $('.selectedCampaignId').text();
        var currentCompanyId = $('#currentCompanyId').text(); 
        var cid = $('#selectedvendorid').val();
        var cname = $('#selectedvendorname').val().split('(')[0];
        var details = $('#fjs_listing_'+id).text();
        var html = '<li id="justadded_' + id +'">' + details +'<span onclick="removeFromArrayAddToCampaign(\'' + id + '\')" class="glyphicon glyphicon-remove remove-icon" id="addedlistings_1"></span></li>';
        //console.log('cid ' + cid + ' html ' + html + ' cname ' + cname + ' camp ' + campid);
        if ($('#vendorselected_'+campid+ '_'+cid).length  ==0) {
            console.log("no vendor added");
            var htm = '<li id="vendorselected_'+campid+ '_' +cid +'">' +
                            '<h3 class="sub-list-item-heading clickfor-show-hide"><span class="glyphicon glyphicon-minus expand-collapse"></span>&nbsp;' + cname +'&nbsp;</h3><div class="assign-dropdown">Assigned to' + 
                                '<select>' +
                                    '<option value="' + cid +'_0" selected="true">Myself</option>';
                            if (currentCompanyId !== cid) {
                                htm = htm + '<option value="' + cid+ '_' + cid+ '">'+ cname +'</option>';
                            } 
                            
                                    
                            htm = htm + '</select></div><ul class="sub-sub-list show-hide-content">'
                                    +'</ul></li>';
                            
                    //  console.log("no vendor added  >> "+ htm); 
            $('#campaign_'+campid).append(htm);
            $('#vendorselected_'+campid+ '_'+cid + ' > ul').append(html);
        } else {
            $('#vendorselected_'+campid+ '_'+cid + ' > ul').append(html);
        }    
    }
    function removeFromArrayAddToCampaign(id) {
        if (confirm("Do you really want to delete this site from campaign!") == true) {
            var index = addtocampaign.indexOf(id);
            if (index > -1) {
                $('#fjs_listing_' + id).removeClass('selected');
                $('#fjs_listing_' + id + ' span').removeClass('glyphicon-remove').addClass('glyphicon-plus').attr('onclick', 'addToCampaign(\'' + id + '\')');
                $('#justadded_' + id).remove();
                addtocampaign.splice(index, 1);
            }
        } else {
            x = "You pressed Cancel!";
        }
            
    }
    function removeFromCampaign(id) {
        removefromcampaign.push(id);
        $('#fjs_listing_' + id).removeClass('selected');
        removeListingFromCampaignd(id,$('.selectedCampaignId').text());
    }
    
    function saveCampaign(id) {
    $('.selectedCampaignId').html(id);
    }    
    function finalCampaignSave() {
        var id = $('.selectedCampaignId').html();
    var temp = $('#campaign_' + id+' > li > div > select > option:selected').map(function(){ return $(this).val();
    }).get();
    var selCam = [];
        for (var i =0; i < temp.length; i++) {
            if (temp[i] != '0') {
                selcam.push(temp[i]);
            }
        }
        
    //for all the selcam ids create pop tasks
    
    }
    
    function finalCampaignUpdate(type,pop) {
            $.ajax({
                    type: 'POST',
                    url: $('#completePath').text() + '/ajax/updateCampaign',
                    data: {
                        'cid': $('.selectedCampaignId').html(),
                        'add':JSON.stringify(addtocampaign.filter( onlyUnique )),
                        'rm':JSON.stringify(removefromcampaign.filter( onlyUnique )),
                        'type' : type,
                        'pop' : pop
                    },
                 success:function(data){
                     
                     
                     addtocampaign = [];
                     removefromcampaign = [];
                     $('#add-site-modal').modal('hide');
                            //$('.selectedCampaignId').html('');
                            $('#campaignListings').html('');
                            console.log(data);
                        if (data === '200') {
                            location.reload();
                        }    
                        
                    },
                    error: function(data) { // if error occured
                          alert("Error occured.please try again");
                          alert(data);
                     }
                   });
    }    
    
    function updateCampaign() {
        var chk = [];
        $("input:checkbox[name=pop]:checked").each(function()
        {
           chk.push($(this).val());
        });
        var id = $('.selectedCampaignId').text();
        
    var temp = $('#campaign_' + id+' > li > div > select > option:selected').map(function(){ return $(this).val();
    }).get();
        console.log(chk + " cmap " + id + ' fdfd '+ temp);
      //  $('.selectedCampaignId').html(id);
        if (chk.length > 0) {
            if (chk.length == 1 && chk[0] == '2') {
                var r = confirm("Are you sure you don't want proof of posting from vendors?");
                if (r == true) {
                 //type 2   
                    finalCampaignUpdate(2,null);
                } else {
                    txt = "You pressed Cancel!";
                }
            } if (chk.length == 1 && chk[0] == '1') {
                //type 1
                finalCampaignUpdate(1,JSON.stringify(temp));
            } else if (chk.length == 2){
                //type 3
                finalCampaignUpdate(3,JSON.stringify(temp));
            }    
        } else {
            alert("Please Select atleast one option");
        }    
    }
    
    function fetchvendors(name,id) {
        console.log(addtocampaign);
        if (addtocampaign.length > 0 || removefromcampaign.length > 0) {
            var r = confirm("You have unsaved campaigns? Do you want to discard changes to these campaigns - " + $('.campaignName').text());
            if (r == true) {
                for (var i=0; i < addtocampaign.length; i++) {
                    $('#justadded_'+ addtocampaign[i]).remove();
                }    
                fetchVendorsPostValidation(name,id);
            } else {
                txt = "You pressed Cancel!";
            }
        } else if (addtocampaign.length == 0 && removefromcampaign.length == 0) {
            fetchVendorsPostValidation(name,id);
        }    

    }
    function fetchVendorsPostValidation(name,id) {
                    addtocampaign = [];
                removefromcampaign = [];
                $('.campaignName').html(name);
                $('#selectedvendorname').val(name);
                $('.selectedCampaignId').html(id);
                $('#campaignListings').children().remove();
                 $.ajax({
                    type: 'POST',
                    url: $('#completePath').text() + '/ajax/vendorsList',
                 success:function(data){
//                     dust.render("vendors", JSON.parse(data), function(err, out) {
//                         $('#vendors').html(out);
//                         console.log(err);
//                     });    

                var template = Mustache.compile($.trim($("#vendorlist").html()));

                  var view = function(service){
                    return template(service);
                  };

                  var settings = {
                    search: {input: '#search_box_vendor' },
                    and_filter_on: true,
                    id_field: 'id' //Default is id. This is only for usecase
                  };

                  return FilterJS(JSON.parse(data), "#vendors", view, settings);



                    },
                    error: function(data) { // if error occured
                          alert("Error occured.please try again");
                          alert(data);
                     }
                   });
    }    
    function fetchCompanyListings(id) {
        
        $('.vendorselection').each(function() {
            $(this).removeClass('selected');
        });
        $('vendor_'+ id).addClass('selected');
//        console.log($('#selectedCampaignId').val() + 'fdsfd');
        $.ajax({
            type: 'POST',
            url: $('#completePath').text() + '/ajax/fetchVendorListing',
            data: {
                'id' : id,
                'cid' : $('.selectedCampaignId').text()
            },
         success:function(data){
//             dust.render("campaignListings", JSON.parse(data), function(err, out) {
//                 $('#campaignListings').html(out);
//                 $('#selectedvendorid').val(id);
//                 $('#selectedvendorname').val($('#vendor_'+id).text());
//                 console.log(err);
//                 var arr = $('#campaignListings > li').map(function(){ return $(this).attr('id').split('_')[1];
//            }).get();
//            
//            
//            for (var i=0; i < addtocampaign.length; i++) {
//                for (var j=0; j<arr.length;j++) {
//                    if (addtocampaign[i] == arr[j]) {
//                        $('#listing_' + arr[j]).addClass('selected');
//                        $('#listing_' + arr[j] + ' span').addClass('glyphicon-remove').removeClass('glyphicon-plus').attr('onclick', 'removeFromArrayAddToCampaign(\'' + arr[j] + '\')');
//                    }    
//                }    
//            }   
//                 
//             });    

             $('#selectedvendorid').val(id);
                 $('#selectedvendorname').val($('#fjs_'+id).text());
                 var arr = $('#campaignListings > li').map(function(){ return $(this).attr('id').split('_')[1];
            }).get();
            
            
            for (var i=0; i < addtocampaign.length; i++) {
                for (var j=0; j<arr.length;j++) {
                    if (addtocampaign[i] == arr[j]) {
                        $('#fjs_listing_' + arr[j]).addClass('selected');
                        $('#fjs_listing_' + arr[j] + ' span').addClass('glyphicon-remove').removeClass('glyphicon-plus').attr('onclick', 'removeFromArrayAddToCampaign(\'' + arr[j] + '\')');
                    }    
                }
            }    

            var template = Mustache.compile($.trim($("#vendorsitelist").html()));
                  var view = function(service){
                    return template(service);
                  };

                  var settings = {
                      //callbacks: filter_callbacks,
                    search: {input: '#search_box_vendor_sites' },
                    and_filter_on: true,
                    id_field: 'lid' //Default is id. This is only for usecase
                  };

                  return FilterJS(JSON.parse(data), "#campaignListings", view, settings);
            },
            error: function(data) { // if error occured
                  alert("Error occured.please try again");
                  alert(data);
             }
           });
    }
    function removeListingFromCampaignd(id, cid) {
//        console.log('ffsdsdfsfss');
    //var cid = $('#addedlistings_' +id).parent().parent().parent().parent().attr('id').split('_')[1];
    if (confirm("Do you really want to delete this site from campaign!") == true) {
            $.ajax({
        type: 'POST',
        url: $('#completePath').text() + '/ajax/removeListingFromCampaign',
        data: {
            'sid': id,
            'cid': cid
        },
        success: function (data) {
            $('#addedlistings_' + id).parent().remove();
            console.log(data);
        },
        error: function (data) { // if error occured
            alert("Error occured.please try again");
            alert(data);
        }
    });
    } else {
        x = "You pressed Cancel!";
    }

}

function cancelAddedSitesToCampaigns() {
    var id = $('.selectedCampaignId').text();
//    console.log(id + " selected campaign");
if (addtocampaign.length > 0 || removefromcampaign.length > 0) {
    if (confirm("Do you really want to discard any changes you made in this campaign!") == true) {
            addtocampaign = [];
        removefromcampaign = [];
        $('.campaignName').html('');
        $('#selectedvendorname').val('');
        $('.selectedCampaignId').html('');
        $('#campaign_' + id).children().find('ul > li[id^=justadded]').remove();
    }

}
    
}
    $('.mon_menu').each(function() {
        $(this).removeClass('active');
    });
    $('.menu_campaign').addClass('active');