var Campaign = Parse.Object.extend("campaign");
var Campaigns = Parse.Collection.extend({
    model: 'campaign'
});
var Site = Parse.Object.extend("site");
var Sites = Parse.Collection.extend({
    model: 'site'
});
var PhotoProof = Parse.Object.extend("photoProof");
var PhotosProofs = Parse.Collection.extend({
    model: 'photoProof'
});
var Task = Parse.Object.extend("task");
var Tasks = Parse.Collection.extend({
    model: 'task'
});
var User = Parse.Object.extend("User");
var Users = Parse.Collection.extend({
    model: 'User'
});
var Organization = Parse.Object.extend("organization");
var Organizations = Parse.Collection.extend({
    model: 'organization'
});
var userZoneAssignment = Parse.Object.extend("userZoneAssignment");
var userZoneAssignments = Parse.Collection.extend({
    model: 'userZoneAssignment'
});




var Monitorly = {
    currentUser: null,
    authorized: false,
    init: function() {
        //console.log('Monitorly init called');
        //Parse.initialize('auRP6Tq2ZdU15kMh60G2vDviEv0MIJGzQmCUn2dp', 'FO9DAkO6U0JbJtOZf0HiBYzBTPI3qB7CMqLAvBPb');
        Parse.initialize(MonitorlyApiKey.key1, MonitorlyApiKey.key2);
    },
    getVendors : function(callback) {
         var query = new Parse.Query(Organization);
        //query.equalTo("organization", {__type: "Pointer", className: "organization", objectId: organizationId});
        var allCampaignsJson = [];
        query.limit(999);
        query.find({
            success: function(campaigns) {
                campaigns.forEach(function(column) {
                    allCampaignsJson.push({
                        'id': column.id,
                        'name': ucWordsJs(column.get('name'))
                        
                    });
                });
                console.log("vendors " + allCampaignsJson);
                sortNewest(allCampaignsJson);
                callback(allCampaignsJson);
            }
        });

    },
    addVendor: function (vendorName,callback) {
        var vendor = new Organization();
        vendor.set('name', vendorName);
        vendor.set('type', 'vendor');
        vendor.save(null, {
            success: function(venInfo) {
                console.log("Adding a new vendor " + vendorName);
                callback(venInfo.id);
            },
            error: function(object, error) {
                console.log(error);
            }
        });

    },
    addSite: function(allSites,callback) {       
        var site = new Site();
        site.set('name', allSites[0]);
        site.set('zone', allSites[1]);
        site.set('mediaType', allSites[2]);
        site.set('vendor', {__type: "Pointer", className: "organization", objectId: allSites[3]});
        site.set("addedBy", {__type: "Pointer", className: "User", objectId: this.currentUser.id});
        site.set("addedByOrganization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});        
        site.set('locality', allSites[4]);
        console.log("lat lng " + allSites[5].B);
        site.set('geoLocation', {__type: "GeoPoint", latitude: allSites[5].k, longitude: allSites[5].B});
        //mapArray.push(site);

        console.log(JSON.stringify(site));
        site.save(null, {
            success: function(siteInfo) {
                console.log("adding a new site " + JSON.stringify(site));
                console.log('success');
                callback(siteInfo.id);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
//callback(1);

    },
    checkLogin: function() {
        this.currentUser = Parse.User.current();
        if (this.currentUser) {
            return true;
        } else {
            return false;
        }
    },
    login: function(username, password, callback) {
        Parse.User.logIn(username, password, {
            success: function(User) {
                Monitorly.authorized = true;
                location.reload();
                //callback();
            },
            error: function(User, error) {
            }
        });
    },
    logout: function(callback) {
        Parse.User.logOut();
        window.location.replace("index.html");
        callback();
    },
    getSite: function(objectId, callback) {
        var query = new Parse.Query(Site);
        query.limit(999);
        query.get(objectId, {
            success: function(site) {
                callback(site);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    addCampaign: function(campaignData, callback){
        var sdate = readDateString(campaignData.campStart);
        var edate = readDateString(campaignData.campEnd);
        /*console.log(sdate);
        console.log(sdate.);
        console.log(new Date(sdate.getTime() + 330*60000));
        return;*/
        var camp = new Campaign();
        camp.set("name", campaignData.campName);
        camp.set("startDate", new Date(sdate.getTime()));// + 330*60000));
        camp.set("endDate", new Date(edate.getTime()));// + 330*60000)); // it should be +23:59:59
        camp.set("organization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        camp.set("createdBy", {__type: "Pointer", className: "User", objectId: this.currentUser.id});
        camp.save(null, {
            success: function(campInfo) {
                var campaignObj = {
                    campaignId: campInfo.id,
                    name: ucWordsJs(campInfo.get('name')),
                    startDate: getFormatedDate(campInfo.get('startDate')),
                    endDate: getFormatedDate(campInfo.get('endDate')),
                    createAt: campInfo.createdAt
                };
                callback(campaignObj);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getCampaign: function(objectId, callback) {
        //console.log('getCampaign called');
        var query = new Parse.Query(Campaign);
        query.limit(999);
        query.get(objectId, {
            success: function(campaign) {
                var campaignObj = {
                    campaignId: campaign.id,
                    name: ucWordsJs(campaign.get('name')),
                    startDate: getFormatedDate(campaign.get('startDate')),
                    endDate: getFormatedDate(campaign.get('endDate')),
                    createAt: campaign.createdAt
                };
                callback(campaignObj);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getCampaigns: function(organizationId, callback) {

        var query = new Parse.Query(Campaign);
        query.equalTo("organization", {__type: "Pointer", className: "organization", objectId: organizationId});
        var allCampaignsJson = [];
        query.limit(999);
        query.find({
            success: function(campaigns) {
                campaigns.forEach(function(column) {
                    allCampaignsJson.push({
                        'campaignId': column.id,
                        'name': ucWordsJs(column.get('name')),
                        'startDate': getFormatedDate(column.get('startDate')),
                        'endDate': getFormatedDate(column.get('endDate')),
                        'createdAt': column.createdAt
                    });
                });
                sortNewest(allCampaignsJson);
                callback(allCampaignsJson);
            }
        });
    },
    getCampaignSites: function(campaignId, callback) {
        // used in campaigns.html,
        var query = new Parse.Query(Task);        
        query.equalTo("campaign", {__type: "Pointer", className: "campaign", objectId: campaignId});
        query.include("site.addedBy", "User");
        //query.include("addedBy", "User");        
        var allSitesJson = [];
        query.limit(999);
        query.find({
            success: function(sitesList) {
                sitesList.forEach(function(column) {
                    var site = column.get('site');                     
                    allSitesJson.push({
                        proofId: column.id,
                        siteId: site.id,
                        name: ucWordsJs(site.get('name')),
                        zone: site.get('zone'),
                        width: site.get('width'),
                        height: site.get('height'),
                        addedBy: site.get('addedBy').id,
                        username: site.get('addedBy').get('username'),
                        campaignId: column.get('campaign').id,
                        photo: column.get('photo')
                    });
                });
                callback(allSitesJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getOrganizationSites: function(organizationId, callback) {        
        var query = new Parse.Query(Site);
        query.equalTo("addedByOrganization", {__type: "Pointer", className: "organization", objectId: organizationId});
        var allSitesJson = [];
        query.limit(999);
        query.find({
            success: function(sitesList) {
                sitesList.forEach(function(column) {
                    allSitesJson.push({
                        'siteId': column.id,
                        'name': ucWordsJs(column.get('name')),
                        'mediaType': column.get('mediaType'),
                        'zone': column.get('zone'),
                        'subZone': column.get('subZone'),
                        'width': column.get('width'),
                        'height': column.get('height'),
                        'addedBy': column.get('addedBy').id,
                        'username': column.get('addedBy').get('username'),                        
                        'geoLocation': column.get('geoLocation')
                    });
                });
                callback(allSitesJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getOrganizationSitesManage: function(organizationId, callback) {        
        var query = new Parse.Query(Site);
        query.equalTo("addedByOrganization", {__type: "Pointer", className: "organization", objectId: organizationId});
        query.include("vendor", "organization");
        var allSitesJson = [];
        query.limit(999);
        query.find({
            success: function(sitesList) {
                sitesList.forEach(function(column) {
                    var vendorName = column.get('vendor') ? column.get('vendor').get('name') : '';                    
                    allSitesJson.push({
                        'siteId': column.id,
                        'name': column.get('name'),
                        'mediaType': column.get('mediaType'),
                        'zone': column.get('zone'),
                        'locality': column.get('locality'),
                        'city': column.get('city'),
                        'vendor': vendorName,
                        'createdAt': column.createdAt
                    });
                });
                sortNewest(allSitesJson);
                callback(allSitesJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getSites: function(callback) {
        var sites = new Sites();
        sites.fetch({
            success: function(ret) {
                callback(ret);
            }
        });
    },
    saveSitesBulk: function(siteData, callback){        
        
        var siteArray = [];
        siteData.forEach(function(st){
            site = new Site();
            site.id = st.siteId;
            site.set("name", st.name);
            site.set("mediaType", st.mediaType);
            site.set("zone", st.zone);
            siteArray.push(site);
        });
            
        Parse.Object.saveAll(siteArray, {
            success: function(siteInfo) {                
                callback();
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    removeZone: function(zoneData, callback) {
        var query = new Parse.Query(User);
        query.get(objectId, {
            success: function(campaign) {

                callback(campaignObj);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    updateTaskUser: function(taskUserData){
        var taskArray = [];
        taskUserData.forEach(function(item){
            task = new Task();
            task.id = item.taskId;
            task.set("assignedUser", {__type: "Pointer", className: "User", objectId: item.userId});            
            taskArray.push(task);
        });
            
        Parse.Object.saveAll(taskArray, {
            success: function(taskInfo) {               
                console.log('success');
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getTasks: function(callback) {
        // page - users_tasks.html
        var query = new Parse.Query(Task);
        //query.equalTo("campaign", {__type: "Pointer", className: "Campaign", objectId: campaignId});
        //query.equalTo("site", {__type: "Pointer", className: "Site", objectId: siteId});
        query.include("campaign");
        query.include("site");
        query.include("assignedUser", "User");
        query.notEqualTo("taskDone", true);
        query.equalTo('addedByOrganization', {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        var allTasksJson = [];
        query.limit(999);
        query.find({
            success: function(tasksList) {                
                tasksList.forEach(function(column) {                    
                    allTasksJson.push({
                        'taskId': column.id,
                        'campaignId': column.get('campaign').id,
                        'campaignName': ucWordsJs(column.get('campaign').get('name')),
                        'siteId': column.get('site').id,
                        'siteName': ucWordsJs(column.get('site').get('name')),
                        'assignedUserId': column.get('assignedUser').id,
                        'assignedUserName': ucWordsJs(column.get('assignedUser').get('username')),                        
                        'taskDone': column.get('taskDone'),
                        'dueDate': column.get('dueDate'),
                        'createdAt': column.createdAt
                    });
                });
                sortTaskData(allTasksJson);
                callback(allTasksJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },    
    getTasksInDate: function(taskDates, callback){
        // page - users_tasks.html
        var query = new Parse.Query(Task);
        // if date range - 7/7/2014 to 8/7/2014
        // then query - 7/7/2014 00:00:00 to 7/7/2014 23:59:59
        var sdate = readDateString(taskDates.taskStart);
        var edate = readDateString(taskDates.taskEnd);
        edate = new Date(edate - 1000);     // less 1 second
        //console.log(sdate);
        //console.log(edate);
        query.include("campaign");
        query.include("site");
        query.include("assignedUser", "User");
        query.notEqualTo("taskDone", true);
        query.equalTo('addedByOrganization', {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        query.greaterThanOrEqualTo("dueDate", new Date(sdate.getTime()));   //+ 330*60000));           // added 5:30:30     
        query.lessThanOrEqualTo("dueDate", new Date(edate.getTime()));      //+ 330*60000 + 86339000));   // added 23:59:59
        var allTasksJson = [];
        query.limit(999);
        query.find({
            success: function(tasksList) {                
                tasksList.forEach(function(column) {                    
                    allTasksJson.push({
                        'taskId': column.id,
                        'campaignId': column.get('campaign').id,
                        'campaignName': ucWordsJs(column.get('campaign').get('name')),
                        'siteId': column.get('site').id,
                        'siteName': ucWordsJs(column.get('site').get('name')),
                        'assignedUserId': column.get('assignedUser').id,
                        'assignedUserName': ucWordsJs(column.get('assignedUser').get('username')),                        
                        'taskDone': column.get('taskDone'),
                        'dueDate': column.get('dueDate'),
                        'createdAt': column.createdAt
                    });
                });
                sortTaskData(allTasksJson);
                callback(allTasksJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getTasksInReports: function(callback) {        
        // page - reports.html
        var query = new Parse.Query(Task);
        //query.equalTo("campaign", {__type: "Pointer", className: "Campaign", objectId: campaignId});
        //query.equalTo("site", {__type: "Pointer", className: "Site", objectId: siteId});
        var orgId = this.currentUser.get('organization').id;        
        query.include("campaign");
        query.include("site");
        query.include("assignedUser", "User");        
        query.equalTo("addedByOrganization", {__type: "Pointer", className: "organization", objectId: orgId});        
        
        var sdate = new Date();
        sdate.setDate(sdate.getDate()-1);
        sdate.setHours(0,0,0,0);
        var edate = new Date();
        edate.setHours(23,59,59);
        
        //query.greaterThanOrEqualTo("dueDate", sdate);
        query.lessThanOrEqualTo("dueDate", edate);  // past & present date, all data
        var allTasksJson = [];
        query.limit(999);
        query.find({
            success: function(tasksList) {                
                tasksList.forEach(function(column) {
                    var mediaType = column.get('site').get('mediaType');
                    if(mediaType == undefined) {
                        mediaType = '-';
                    } else {
                        mediaType = ucWordsJs(mediaType);
                    }                    
                    allTasksJson.push({
                        'taskId': column.id,
                        'campaignId': column.get('campaign').id,
                        'campaignName': ucWordsJs(column.get('campaign').get('name')),
                        'siteId': column.get('site').id,
                        'siteName': ucWordsJs(column.get('site').get('name')),
                        'mediaType': mediaType,
                        'assignedUserId': column.get('assignedUser').id,
                        'assignedUserName': ucWordsJs(column.get('assignedUser').get('username')),                        
                        'taskDone': column.get('taskDone'),
                        'problem': column.get('problem'),
                        'dueDate': column.get('dueDate'),
                        'createdAt': column.createdAt
                    });
                });                
                allTasksJson.sort(function(a, b) {                    
                    return b.dueDate - a.dueDate;
                });
                callback(allTasksJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getTaskPhotoProof: function(taskData, callback){
        // page - lightbox for reports page
        // console.log('task id - ' + taskId);
        // today and yesterday
        var galleryDate = taskData.dueDate;
        var galleryDateStart = new Date();
        var galleryDateEnd = new Date();
        galleryDateStart.setDate(galleryDate.getDate());
        galleryDateStart.setHours(0,0,0,0);        
        galleryDateEnd.setDate(galleryDate.getDate());
        galleryDateEnd.setHours(23,59,59);
        console.log(galleryDateStart);
        console.log(galleryDateEnd);
        
        
        var query = new Parse.Query(PhotoProof);        
        query.equalTo("task", {__type: "Pointer", className: "task", objectId: taskData.taskId});
        query.greaterThanOrEqualTo("timestamp", galleryDateStart);
        query.lessThanOrEqualTo("timestamp", galleryDateEnd);
        query.include("problem");
        query.include("clickedBy", "User");
        var allTaskPhotoProofJson = [];
        query.limit(999);
        query.find({
            success: function(ppList) {                
                ppList.forEach(function(column) {                    
                    allTaskPhotoProofJson.push({
                        'photoProofId': column.id,
                        'taskId': column.get('task').id,
                        'clickedBy': ucWordsJs(column.get('clickedBy').get('username')),
                        'clickedById': column.get('clickedBy').id,
                        'photo': column.get('photo'),
                        'dueDate': column.get('timestamp'),
                        'problemId': column.get('problem'),
                        //'problemInstalltion': column.get('problem').get('installation'),
                        //'problemLighting': column.get('problem').get('lighting'),
                        //'problemObstruction': column.get('problem').get('obstruction')
                        
                        //'photoName': column.get('photo').name(),
                        //'photoUrl': column.get('photo').url(),
                        //'campaignId': column.get('campaign').id,
                        //'siteId': column.get('site').id,
                        'assignedUserName': ucWordsJs(column.get('clickedBy').get('username'))
                    });
                });
                
                allTaskPhotoProofJson.sort(function(a, b) {
                    return b.dueDate - a.dueDate;
                });                 
                callback(allTaskPhotoProofJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getPhotoProofs: function(callback) {
        var query = new Parse.Query(PhotoProof);
        //query.equalTo("campaign", {__type: "Pointer", className: "Campaign", objectId: campaignId});
        //query.equalTo("site", {__type: "Pointer", className: "Site", objectId: siteId});
        //query.include("campaign");
        //query.include("site");
        query.include("task");
         
        // today and yesterday
        var sdate = new Date();
        sdate.setDate(sdate.getDate()-1);
        sdate.setHours(0,0,0,0);
        var edate = new Date();
        edate.setHours(23,59,59);
        
        query.greaterThanOrEqualTo("timestamp", sdate);
        query.lessThanOrEqualTo("timestamp", edate);
        query.include("clickedBy", "User");        
        var innerQuery = new Parse.Query(Task);
        innerQuery.equalTo("addedByOrganization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        query.matchesQuery("task", innerQuery);      
        var allPhotoProofJson = [];
        query.limit(999);
        query.find({
            success: function(photoProofsList) {                
                photoProofsList.forEach(function(column) {                    
                    allPhotoProofJson.push({
                        'photoProofId': column.id,
                        'taskId': column.get('task').id,                        
                        //'campaignId': column.get('campaign').id,
                        'campaignName': ucWordsJs(column.get('campaignName')),
                        //'siteId': column.get('site').id,
                        'siteName': ucWordsJs(column.get('siteName')),
                        'clickedBy': column.get('clickedBy').id,
                        'assignedUserName': ucWordsJs(column.get('clickedBy').get('username')),
                        'photo': column.get('photo'),
                        'dueDate': column.get('timestamp'),
                        //'photoName': column.get('photo').get('name'),
                        //'photoUrl': column.get('photo').get('url'),
                        'problem': column.get('problem')
                    });
                });
                allPhotoProofJson.sort(function(a, b) {
                    return b.dueDate - a.dueDate;
                });
                callback(allPhotoProofJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getProofsForCampaignAndSite: function(campaignId, siteId, callback) {
        var query = new Parse.Query(Proof);
        query.equalTo("campaign", {__type: "Pointer", className: "Campaign", objectId: campaignId});
        query.equalTo("site", {__type: "Pointer", className: "Site", objectId: siteId});
        query.include("creator");
        query.limit(999);
        query.find({
            success: function(proofs) {
                callback(proofs);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },    
    getVendors: function(callback){
        // this is only for autocomplete, mysites vendor
        var query = new Parse.Query(Organization);
        query.equalTo("type", "vendor");
        var allVendorsJson = [];
        query.limit(999);
        query.find({
            success: function(allOrganizations) {
                allOrganizations.forEach(function(column) {
                    //allVendorsJson.push(column.get('name'));
                    allVendorsJson.push({
                        'id': column.id,
                        'value': column.get('name'),
                        //'type': column.get('type')                        
                    });
                });                
                callback(allVendorsJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getSiteNames: function(callback){
        // this is only for autocomplete, campaign sites vendor
        // only sites that matches logged in user's organization
        var query = new Parse.Query(Site);
        query.equalTo('addedByOrganization', {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        //query.include('userZoneAssignment');
        //query.include('userZoneAssignment.userOrganization', 'vendor');
        //query.containedIn('zone', 'userZoneAssignment.assignedZones');
        //query.equalTo('userZoneAssignment.userOrganization', this.currentUser.get('organization').id);
        var allSiteACJson = [];
        query.limit(999);
        query.find({
            success: function(allSites) {
                allSites.forEach(function(column) { 
                    //allVendorsJson.push(column.get('name'));
                    allSiteACJson.push({
                        'siteId': column.id,
                        'siteName': column.get('name'),
                        'siteZone': column.get('zone'),
                        'siteVendorId': column.get('vendor').id
                    });
                });                
                callback(allSiteACJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getLoggedInUserZoneAssignment: function(callback){
        var query = new Parse.Query(userZoneAssignment);
        var orgId = this.currentUser.get('organization').id;        
        query.equalTo("userOrganization", {__type: "Pointer", className: "organization", objectId: orgId});
        query.include('user');
        var allUserZoneAssignment = [];
        query.limit(999);
        query.find({
            success: function(uza) {                
                uza.forEach(function(column) {
                    allUserZoneAssignment.push({
                        userId: column.get('user').id,
                        username: column.get('user').get('username'),
                        role: column.get('user').get('role'),
                        userOrganizationId: column.get('userOrganization').id,
                        assignedZones: column.get('assignedZones')                        
                    });
                });                
                callback(allUserZoneAssignment);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getUsers: function(callback) {
        var query = new Parse.Query(User);
        //query.include('userZoneAssignment');
        query.equalTo("organization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        var allUsersJson = [];
        query.limit(999);
        query.find({
            success: function(usersList) {
                usersList.forEach(function(column) {
                    allUsersJson.push({
                        'userId': column.id,
                        'username': ucWordsJs(column.get('username')),
                        'email': column.get('email'),
                        'mobile': column.get('MobileNumber'),
                        //'assignedZones': column.get('assignedZones'),
                        'createdAt': column.createdAt
                    });
                });
                sortNewest(allUsersJson);
                callback(allUsersJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    getUsersZones: function(callback) {
        var query = new Parse.Query(userZoneAssignment);
        var allUserZonesJson = [];
        query.limit(999);
        query.find({
            success: function(userZones) {
                userZones.forEach(function(column) {
                    allUserZonesJson.push({
                        'uzaId': column.id,
                        'userId': column.get('user').id,
                        'assignedZones': column.get('assignedZones'),
                        'createdAt': column.createdAt
                    });
                });                
                callback(allUserZonesJson);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    updateUserZone: function(zoneData) {
        // for update we've to fetch array and then modify array
        var uza = new userZoneAssignment();
        uza.id = zoneData.uzaId;
        uza.set("user", {__type: "Pointer", className: "User", objectId: zoneData.userId});
        uza.set("userOrganization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        uza.set("assignedZones", zoneData.zones);
        uza.save(null, {
            success: function(userInfo) {
                              
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    addUserZone: function(zoneData, callback){
        var uza = new userZoneAssignment();      
        uza.set("user", {__type: "Pointer", className: "User", objectId: zoneData.userId});
        uza.set("userOrganization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        uza.set("assignedZones", zoneData.zones);        
        
        uza.save(null, {
            success: function(uzaInfo) {                
                callback(uzaInfo);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    addUser: function(userData, callback){
        var user = new User();
        user.set("username", userData.userName);
        user.set("password", userData.password);
        user.set("email", userData.userEmail);        
        user.set("MobileNumber", parseInt(userData.userMobile));
        user.set("role", "monitor");
        user.set("organization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});        
        user.save(null, {
            success: function(userInfo) {
                var newUserData = {
                    'userId': userInfo.id,
                    'username': ucWordsJs(userInfo.get('username')),
                    'email': userInfo.get('email'),
                    'mobile': userInfo.get('MobileNumber'),
                    //'assignedZones': column.get('assignedZones'),
                    'createdAt': userInfo.createdAt
                };
                callback(newUserData);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },    
    updateUser: function(userData, callback) {
        // var query = new Parse.Query(User);
        /*var User = Parse.Object.extend("User");
         var userRow = new User();
         userRow.id = userData.userId;
         
         userRow.set("username", userData.username);
         userRow.set("email", userData.email);
         userRow.set("mobile", userData.mobile);
         
         // Save
         userRow.save(null, {
         success: function(point) {
         // Saved successfully.
         },
         error: function(point, error) {
         // The save failed.
         // error is a Parse.Error with an error code and description.
         }
         });*/

        var query = new Parse.Query(User);
        query.equalTo('objectId', userData.userId);
        query.first({
            success: function(userInfo) {
                userInfo.set('username', userData.username);
                userInfo.set('email', userData.email);
                userInfo.set('MobileNumber', userData.mobile);
                userInfo.save()
                callback(true);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    saveCampaignSite: function(siteCampData, callback) {
        var start = readDateString(siteCampData.startDate);
        var end = readDateString(siteCampData.endDate);
        var Task = new Parse.Object.extend('task');
        var mapArray = [];
        while(start <= end){            
            var newDate = start.setDate(start.getDate());
            task = new Task();
            task.set("assignedUser", {__type: "Pointer", className: "User", objectId: siteCampData.userId});
            task.set("campaign", {__type: "Pointer", className: "campaign", objectId: siteCampData.campaignId});
            task.set("site", {__type: "Pointer", className: "site", objectId: siteCampData.siteId});
            task.set("addedByOrganization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
            task.set("dueDate", new Date(newDate));
            
            mapArray.push(task);
            
            var newDate = start.setDate(start.getDate() + 1);
            start = new Date(newDate);            
        }
        Parse.Object.saveAll(mapArray, {
            success: function(siteInfo) {               
                callback(true);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    saveVendorSite: function(siteData, callback) {
        var userId = this.currentUser.id;
        var orgId = this.currentUser.get('organization').id;
        var vendor = new Organization();
        vendor.set("name", siteData.vendor);
        vendor.set("type", "vendor");
        vendor.save(null,  { 
            success: function(vendorInfo){                
                console.log('save site after vendor - ' + vendorInfo.id + " - " + userId + " - " + orgId);
                var site = new Site();
                var gp = new Parse.GeoPoint({
                    latitude: parseFloat(siteData.lat),
                    longitude: parseFloat(siteData.lng)
                });
                site.set("name", siteData.name);
                site.set("mediaType", siteData.mediaType);                
                site.set("geoLocation", gp);
                if(siteData.zone) {
                    site.set("zone", siteData.zone);
                }                
                site.set("vendor", {__type: "Pointer", className: "organization", objectId: vendorInfo.id});
                site.set("addedBy", {__type: "Pointer", className: "User", objectId: userId});
                site.set("addedByOrganization", {__type: "Pointer", className: "organization", objectId: orgId});
                site.set("locality", siteData.locality);
                site.save(null, {
                    success: function(siteInfo) {
                        // add a vendor here                        
                        callback(true);
                    },
                    error: function(object, error) {
                        console.log(error);
                    }
                });
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    saveSite: function(siteData, callback) {
        var site = new Site();
        var gp = new Parse.GeoPoint({
            latitude: parseFloat(siteData.lat),
            longitude: parseFloat(siteData.lng)
        });
        site.set("name", siteData.name);
        site.set("mediaType", siteData.mediaType);        
        site.set("geoLocation", gp);
        if(siteData.zone) {
            site.set("zone", siteData.zone);
        }
        site.set("vendor", {__type: "Pointer", className: "organization", objectId: siteData.vendorId});
        site.set("addedBy", {__type: "Pointer", className: "User", objectId: this.currentUser.id});
        site.set("addedByOrganization", {__type: "Pointer", className: "organization", objectId: this.currentUser.get('organization').id});
        site.set("locality", siteData.locality);
        site.save(null, {
            success: function(siteInfo) {                                
                callback(true);
            },
            error: function(object, error) {
                console.log(error);
            }
        });
    },
    saveCampaign: function(name) {
        var newCampaign = new Campaign();
        newCampaign.save({
            Name: name
        }).then(function(obj) {
            Monitorly.getCampaigns();
        });
    }
    /*getCampaignDetailsView: function(objectId, callback){
     var campaignArr = this.getCampaign(objectId);
     console.log(campaignArr);
     }*/

};
function loginSuccessful() {
    //console.log(currentUser);
    //console.log('login successful');
    $('#username').val('');
    $('#password').val('');
    $('#LoginModal').modal('hide');
    $("#authButtons").html('<button type="button" class="btn btn-primary" id="authLogout">Logout</button>');
}
function logoutSuccessful() {
    //window.location.replace("index.html");
    //console.log(currentUser);
    //console.log('logout successful');
    $("#authButtons").html('<button class="btn btn-primary" data-toggle="modal" data-target="#LoginModal">Login</button>');
}
function ucWordsJs(str) {
    str = str.toLowerCase();
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function($1) {
        return $1.toUpperCase();
    });
}
function strArryToLower(strArr) {                
    return $.map(strArr, function(n,i){ return n.toLowerCase();});
}
function validateAlphaSpace(str) {
    var regex = /^[a-zA-Z ]*$/;
    return str.match(regex) ? true : false; 
}
function validateNumeric(str) {
    var regex = /^\d+$/;
    return str.match(regex) ? true : false; 
}
function validateEmail(str) {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return str.match(regex) ? true : false; 
}
function removeDuplicateValues(data) {
    var newData = [];
    for (var i = 0; i < data.length; i++) {
        var f = newData.filter(function(e, _) {
            return e.siteId == data[i].siteId;
        });
        if (!f.length) {
            newData.push(data[i]);
        }
    }
    return newData;
}
function sortNewest(arrData) {
    arrData.sort(function(a, b) {
        // Turn your strings into dates, and then subtract them
        // to get a value that is either negative, positive, or zero.
        return b.createdAt - a.createdAt;
    });
    return arrData;
}
function sortTaskData(taskData) {
    // task data will be sorted 
    taskData.sort(function(a,b) {
        var textA = a.assignedUserName;
        var textB = b.assignedUserName;
        var date1 = a.dueDate;
        var date2 = b.dueDate;
        //return b.assignedUserName - a.assignedUserName;        
        if(date1 < date2) return 1;
        if(date1 > date2) return -1;
        if(textA < textB) return -1;
        if(textA > textB) return 1;
        return 0;        
    });
    /*taskData.sort(function(a,b){
        var date1 = a.dueDate;
        var date2 = b.dueDate;
        return (date1 > date2) ? 1 : (date1 < date2) ? -1 : 0;
    });*/
    
    // first by name
    // then by dueDate
}
function readDateString(dateStr) {
    var parts = dateStr.split('/');
    var date = new Date(parseInt(parts[2], 10),     // year
                        parseInt(parts[1], 10)-1,     // month, starts with 0
                        parseInt(parts[0], 10));    // day
    
    return date;
}
function returnFormatedDateString(val){
    var days = val.getDate(),
    month = val.getMonth()+1,
    year = val.getFullYear();
    return days + "/" + month + "/" + year;
}
function getFormatedDate(date) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }
    var d = new Date(date);    
    return [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/');
}
function getFormatedDateTime(date) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }
    var d = new Date(date);
    var dateTime = [pad(d.getDate()), pad(d.getMonth() + 1), d.getFullYear()].join('/');
    dateTime += " " + [d.getHours(), d.getMinutes()].join(':');
    return dateTime;
}
function getUrlParameter(param)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == param)
        {
            return sParameterName[1];   // otherwise                    
        }
    }
    return false;   // if param donot exists
}
$(function() {
    // Init the dialog
    /*$('#dialog-newcampaign').dialog({
     autoOpen: false,
     height: 163,
     width: 350,
     modal: true,
     buttons: {
     'Cancel': function () {
     $(this).find('input:first').val('');
     $(this).dialog('close');
     },
     'Create Campaign': function () {
     var campaignName = $(this).find('input:first').val();
     $(this).find('input:first').val('');
     Monitorly.saveCampaign(campaignName);
     $(this).dialog('close');
     }
     }
     });
     $('.btnNewCampaign').click(function (e) {
     e.preventDefault();
     $('#dialog-newcampaign').dialog('open');
     });
     $('.btnAllSites').click(function (e) {
     e.preventDefault();
     window.location.href = 'index.php?page=sites';
     });
     $('.btnViewCaptures').click(function (e) {
     e.preventDefault();
     window.location.href = 'index.php?page=captures';
     });*/

});
