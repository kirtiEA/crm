var Campaign = Parse.Object.extend("Campaign");
var Campaigns = Parse.Collection.extend({
    model: 'Campaign'
});
var Site = Parse.Object.extend("Site");
var Sites = Parse.Collection.extend({
    model: 'Site'
});
var Proof = Parse.Object.extend("Proof");
var Proofs = Parse.Collection.extend({
    model: 'Proof'
});

var Monitorly = {
    authorized: false,
    init: function () {
        Parse.initialize('auRP6Tq2ZdU15kMh60G2vDviEv0MIJGzQmCUn2dp', 'FO9DAkO6U0JbJtOZf0HiBYzBTPI3qB7CMqLAvBPb');
    },
    login: function (username, password, callback) {
        Parse.User.logIn(username, password, {
            success: function(user) {
                Monitorly.authorized = true;
                callback();
            },
            error: function(user, error) {
            }
        });
    },
    logout: function (callback) {
        Parse.User.logOut();
        callback();
    },
    getSite: function (objectId, callback) {
        var query = new Parse.Query(Site);
        query.get(objectId, {
            success: function (site) {
                callback(site);
            },
            error: function (object, error) {
                console.log(error);
            }
        });
    },
    getCampaign: function (objectId, callback) {
        var query = new Parse.Query(Campaign);
        query.get(objectId, {
            success: function (campaign) {
                callback(campaign);
            },
            error: function (object, error) {
                console.log(error);
            }
        });
    },
    getCampaigns: function (callback) {
        var campaigns = new Campaigns();
        // Clear folder list
        $('div.right > div.row').find('div.item').each(function (index) {
            if (!$(this).hasClass('addNew')) {
                $(this).remove();
            }
        });
        // Clear sidebar list
        $('div.container.main ul:first').find('li').each(function (index) {
            if ($(this).find('button').length === 0) {
                $(this).remove();
            }
        });
        campaigns.fetch({
            success: function (ret) {
                callback(ret);
            }
        });
    },
    getSites: function (callback) {
        var sites = new Sites();
        sites.fetch({
            success: function (ret) {
                callback(ret);
            }
        });
    },
    getProofsForCampaignAndSite: function (campaignId, siteId, callback) {
        var query = new Parse.Query(Proof);
        query.equalTo("campaign", { __type: "Pointer", className: "Campaign", objectId: campaignId });
        query.equalTo("site", { __type: "Pointer", className: "Site", objectId: siteId });
        query.include("creator");
        query.find({
            success: function (proofs) {
                callback(proofs);
            },
            error: function (object, error) {
                console.log(error);
            }
        });
    },
    saveCampaign: function (name) {
        var newCampaign = new Campaign();
        newCampaign.save({
            Name: name
        }).then(function (obj) {
            Monitorly.getCampaigns();
        });
    }
};

$(function() {
    // Init the dialog
    $('#dialog-newcampaign').dialog({
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
    });

});
