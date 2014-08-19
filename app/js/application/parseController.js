Parse.initialize("l5AHZqBjinmzFohU6Fg1auvO9fjovLamKgiGs2zV", "47yxpa1jWIKq4vydSSefqCqFChJ5aK4xzG2EvJD9");

var Campaign = Parse.Object.extend("campaign");
var query = new Parse.Query(Campaign);

query.find({
    success: function(results) {
        console.log("Successfully retrieved " + results.length + " scores.");
        // Do something with the returned Parse.Object values
        for (var i = 0; i < results.length; i++) {
            var object = results[i];
            //console.log(object.id + ' - ' + object.get('name'))	    	
            var html = '<div id="' + object.id + '" class="item"><div class="left"><h3>' + object.get('name') + '<span>' + object.get('siteCount') + '</span></h3><p class="date">' + getFormatedDate(object.get('startDate')) + ' - ' + getFormatedDate(object.get('endDate')) + '</p></div><div class="right"><button class="btn-success inverse">Report</button><a class="controller"></a></div><div class="detailed"></div></div>';
            //console.log(html);
            $('.addons').append(html);
        }

        showCampaign();
    },
    error: function(error) {
        alert("Error: " + error.code + " " + error.message);
    }
});

function getFormatedDate(date) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }
    var d = new Date(date);
    return [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/');
}

function fetchCampaignDetails(campaignId) {
    //console.log(campaignId);
    var Campaign = Parse.Object.extend("campaign");
    var Site = Parse.Object.extend("site");
    var query = new Parse.Query(Site);

    var cmp = new Campaign();
    cmp.id = campaignId;
    query.equalTo("campaign", cmp);
    //query.equalTo("campaign", campaignId);

    query.include('User');                      // << check this
    /*var User = Parse.Object.extend('User');
     query.include('User');
     query.equalTo('addedBy', User);*/
    
    query.find({
        success: function(results) {
            console.log("Successfully retrieved " + results.length + " scores.");
            $('div#' + campaignId).find('.detailed').html('');	// clear the html
            var addedByIdsArr = [];
            var usernameArr = [];
            var subZonesArr = [];
            for (var i = 0; i < results.length; i++) {
                var object = results[i];
                var siteId = object.id;
                
                var html = '<ul class="mainlist">';
                html += '<li class="zones">' + object.get('zone') + '<span class="info">3</span>';               

                // get the addedBy user name
                var addedBy = object.get('addedBy');
                addedBy = addedBy.id;
                addedByIdsArr.push(addedBy);
                //console.log('addedBy = ' + addedBy);
                var User = Parse.Object.extend("User");
                var queryUser = new Parse.Query(User);
                var username = null;
                
                // a href class because, multiple such elements will be there
                html += '<span class="assignee">Assigned to <a href="#" class="'+addedBy+'"></a>';
                
                queryUser.get(addedBy, {
                    success: function(userDetails) {
                        username = userDetails.get('username');                        
                        usernameArr.push(username);
                        
                        // assigned to usernames                        
                        for(var j=0; j<addedByIdsArr.length; j++) {
                            //console.log(addedByIdsArr[j] +" "+usernameArr[j]);
                            $("a."+addedByIdsArr[j]).html(usernameArr[j]);                            
                        }                        
                    },
                    error: function(error) {
                        alert("Error: " + error.code + " " + error.message);
                    }
                });
                
                /*html += '<div class="popover right" id="popoverContent" style="display:none;"><div class="arrow"></div><div class="popover-content"><select multiple="multiple" data-title="Assign to" class="assign" name="mediatypeid[]" id="mediatypeid" style="display:none;"><option value="multiselect-all"> All</option><option value="1">Rahul</option><option value="9">Dhruv</option><option value="4">Nigel</option></select></div></div>';*/

                html += '</span>';
                html += '<ul class="sub '+siteId+'" style="display:none;" ><li>Lajpat Nagar</li><li>Safdarjung</li><li>Abc</li></ul>';
                html += '</ul>';
                
                
                // find the sub zones for same zone and campaign
                //console.log('zone - ' + object.get('zone'));
                //console.log('campaign - ' + object.get('campaign').id);
                             
                query.equalTo("zone", object.get('zone'));
                query.equalTo("campaign", object.get('campaign'));
                query.find({
                    success: function(resultSubZones) {
                        for (var k = 0; k < resultSubZones.length; k++) {
                            var subObj = resultSubZones[k];
                            subZonesArr[k] = subObj.get('subZone');
                            
                            //console.log(subObj.get('subZone'));
                            //console.log(subZonesArr);
                        }
                    },
                    error: function(error) {
                        alert("Error: " + error.code + " " + error.message);
                    }
                });                
                                
                $('div#' + campaignId).find('.detailed').append(html);
            }
            
            showZones();
        },
        error: function(error) {
            alert("Error: " + error.code + " " + error.message);
        }
    });


}