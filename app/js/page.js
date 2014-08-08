(function ($, document, undefined) {
    'use strict';

    var emptyDropArea = '<span>Drag the listings here</span>',
        addedIds = [],
        allSitesIds = [],
        myCampaign,
        map;

    function showModalDialog(id, dlgClass, content) {
        if ($('#' + id).length === 0) {
        var dialog = $('<div></div>')
            .addClass('modal')
            .addClass('fade')
            .addClass(dlgClass)
            .attr('id', id)
            .attr('tabindex', -1)
            .attr('role', 'dialog')
            .attr('aria-labelledby', id)
            .attr('aria-hidden', true)
            .html([
                '<div class="modal-dialog">',
                '   <div class="modal-content">',
                '       <div class="modal-body">' + content + '</div>',
                '   </div>',
                '</div>'
            ].join(''));
            $('body').prepend(dialog);
        } else {
            // Just set the content
            $('#' + id + ' div.modal-dialog div.modal-content div.modal-body:first').html(content);
        }
        console.log($('#' + id));
        $('#' + id).modal('show');
    }

    function deleteAddedSiteContainer(id) {
        var i,
            relation;
        $('div#addedsite-' + id).remove();
        for (i = 0; i < addedIds.length; i += 1) {
            if (addedIds[i] === id) {
                // Splice this one out
                addedIds.splice(i, 1);
            }
        }
        if (addedIds.length === 0) {
            // Completely empty, add back the empty span
            $('div.row div.left.dnd:first').html(emptyDropArea);
        }
        if (typeof myCampaign !== 'undefined') {
            Monitorly.getSite(id, function (site) {
                var allSites;
                allSites = myCampaign.get('Sites');
                for (i = 0; i < allSites.length; i += 1) {
                    if (allSites[i].id === id) {
                        allSites.splice(i, 1);
                    }
                }
                myCampaign.set('Sites', allSites);
                myCampaign.save();
            });
        }
        refreshSiteContainers();
    }

    function createAddedSiteContainer(id, name, errors, img) {
        var dropContainer = $('div.row div.left.dnd:first'),
            siteContainer,
            relation,
            found,
            i;
        if (dropContainer.html() === emptyDropArea) {
            dropContainer.html('<div class="row" id="droppable"></div>');
        }
        siteContainer = $('<div></div>')
            .addClass('mosaicview')
            .attr('id', 'addedsite-' + id)
            .html('<span class="close"></span><h3>' + name + '</h3><img src="' + img + '" />');
        // Check for errors
        if (errors.length > 0) {
            // Add the errors
        }
        dropContainer.find('div.row:first').append(siteContainer);
        addedIds.push(id);
        if (typeof myCampaign !== 'undefined') {
            Monitorly.getSite(id, function (site) {
                var allSites = myCampaign.get('Sites');
                if (typeof allSites === 'undefined') {
                    console.log('undefined, adding the site');
                    myCampaign.set('Sites', [site]);
                    myCampaign.save();
                } else {
                    found = false;
                    for (i = 0; i < allSites.length; i++) {
                        if (allSites[i].id === site.id) {
                            found = true;
                            break;
                        }
                    }
                    if (found === false) {
                        allSites.push(site);
                        myCampaign.set('Sites', allSites);
                        myCampaign.save();
                    }
                }
            });
        }
        refreshSiteContainers();
        // Add the event handler
        $('div#addedsite-' + id + ':first').find('span.close:first').click(function (e) {
            deleteAddedSiteContainer(id);
        });
    }

    function createDraggableSiteContainer(id, name, img) {
        var siteContainer = $('<div></div>')
            .addClass('mosaicview')
            .attr('id', 'site-' + id)
            .html('<h3>' + name + '</h3><img src="' + img + '" />');
        $('div.row div.right div.row').append(siteContainer);
        // Add draggabale...
        $('div#site-' + id).draggable({
            cursor: 'move',
            containment: 'document',
            revert: true,
            stack: 'div#draggable',
            helper: 'clone',
            zIndex: 300
        });
        allSitesIds.push(id);
    }

    function createReportContainer(id, name, errors, img, campaignId, page) {
        var content = '',
            container,
            i,
            type = $('input#type:first').val();
        // Errors?
        if (errors.installationErrors.length || errors.obstructionErrors.length || errors.lightingErrors.length) {
            // There are errors...
            for (i = 0; i < errors.installationErrors.length; i += 1) {
                errors.installationErrors[i] = '<span>' + errors.installationErrors[i] + '</span>';
            }
            for (i = 0; i < errors.lightingErrors.length; i += 1) {
                errors.lightingErrors[i] = '<span>' + errors.lightingErrors[i] + '</span>';
            }
            for (i = 0; i < errors.obstructionErrors.length; i += 1) {
                errors.obstructionErrors[i] = '<span>' + errors.obstructionErrors[i] + '</span>';
            }
        }
        content = [
            '<h3>' + name + '</h3>',
            '<div class="left"><img src="' + img + '" /></div>',
            '<div class="right">',
            '<h4><strong>Installation status ' + ((errors.installationErrors.length > 0) ? '<span class="haserrors"></span>' : '<span class="errorfree"></span>') + '</strong>',
            ((errors.installationErrors.length > 0) ? '<br />Installation problem:<br />' + errors.installationErrors.join('<br />') : ''),
            '</h4>',
            '<h4><strong>Lighting status ' + ((errors.lightingErrors.length > 0) ? '<span class="haserrors"></span>' : '<span class="errorfree"></span>') + '</strong>',
            ((errors.lightingErrors.length > 0) ? '<br />Lighting problem:<br />' + errors.lightingErrors.join('<br />') : ''),
            '</h4>',
            '<h4><strong>Obstruction status ' + ((errors.obstructionErrors.length > 0) ? '<span class="haserrors"></span>' : '<span class="errorfree"></span>') + '</strong>',
            ((errors.obstructionErrors.length > 0) ? '<br />Obstruction problem:<br />' + errors.obstructionErrors.join('<br />') : ''),
            '</h4>',
            '</div>'
        ].join('');
        if (page === 'report-pdf') {
            content = content.replace(new RegExp('<span class="haserrors"></span>', 'g'), ' has errors');
            content = content.replace(new RegExp('<span class="errorfree"></span>', 'g'), ' ok');
        }
        container = $('<div></div>')
            .addClass('item')
            .html(content);
        if (errors.installationErrors.length || errors.obstructionErrors.length || errors.lightingErrors.length) {
            container.addClass('thereAreErrors');
        } else {
            container.addClass('thereAreNoErrors');
        }
        if (type === 'errors') {
            if (container.hasClass('thereAreErrors')) {
                container
                    .appendTo('div#reportitemlist');
            }
        } else if (type === 'ok') {
            if (container.hasClass('thereAreNoErrors')) {
                container
                    .appendTo('div#reportitemlist');
            }
        } else {
            container
                .appendTo('div#reportitemlist');
        }
    }

    function createSiteContainer(id, name, errors, img, campaignId) {
        var i;
        var row = '';
        var siteContainer = $('<div></div>')
            .addClass('mosaicview')
            .attr('id', 'site-' + id)
            .html('<h3>' + name + '</h3><img src="' + img + '" />');
        // Errors?
        if (errors.installationErrors.length || errors.obstructionErrors.length || errors.lightingErrors.length) {
            // There are errors...
            for (i = 0; i < errors.installationErrors.length; i += 1) {
                errors.installationErrors[i] = '<span>' + errors.installationErrors[i] + '</span>';
            }
            for (i = 0; i < errors.lightingErrors.length; i += 1) {
                errors.lightingErrors[i] = '<span>' + errors.lightingErrors[i] + '</span>';
            }
            for (i = 0; i < errors.obstructionErrors.length; i += 1) {
                errors.obstructionErrors[i] = '<span>' + errors.obstructionErrors[i] + '</span>';
            }
            siteContainer.addClass('error');
            row = '<div class="row"><p>Reporter: <strong>' + errors.reporter + '</strong><span>Uploaded: <strong>' + errors.reportedAt + '</strong></span></p><p></p></div>';
        }
        siteContainer.click(function (e) {
            e.preventDefault();
            showModalDialog('StatusModal', 'login', [
                '<h3>' + name + '<span class="edit"><a href="#" id="btnReportErrors-' + id + '">edit</a></span></h3>',
                '<div class="left"><img src="' + img + '" /></div>',
                '<div class="right">',
                '<h4><strong>Installation status ' + ((errors.installationErrors.length > 0) ? '<span class="haserrors"></span>' : '<span class="errorfree"></span>') + '</strong>',
                ((errors.installationErrors.length > 0) ? '<br />Installation problem:<br />' + errors.installationErrors.join('<br />') : ''),
                '</h4>',
                '<h4><strong>Lighting status ' + ((errors.lightingErrors.length > 0) ? '<span class="haserrors"></span>' : '<span class="errorfree"></span>') + '</strong>',
                ((errors.lightingErrors.length > 0) ? '<br />Lighting problem:<br />' + errors.lightingErrors.join('<br />') : ''),
                '</h4>',
                '<h4><strong>Obstruction status ' + ((errors.obstructionErrors.length > 0) ? '<span class="haserrors"></span>' : '<span class="errorfree"></span>') + '</strong>',
                ((errors.obstructionErrors.length > 0) ? '<br />Obstruction problem:<br />' + errors.obstructionErrors.join('<br />') : ''),
                '</h4>',
                '</div>',
                row
            ].join(''));
            $(document).on('click', 'a[id^=btnReportErrors-]', function () {
                if (errors.installationErrors.length || errors.obstructionErrors.length || errors.lightingErrors.length) {
                    // There are errors...
                    for (i = 0; i < errors.installationErrors.length; i += 1) {
                        errors.installationErrors[i] = errors.installationErrors[i].replace(/<\/*span>/g, '');
                    }
                    for (i = 0; i < errors.lightingErrors.length; i += 1) {
                        errors.lightingErrors[i] = errors.lightingErrors[i].replace(/<\/*span>/g, '');
                    }
                    for (i = 0; i < errors.obstructionErrors.length; i += 1) {
                        errors.obstructionErrors[i] = errors.obstructionErrors[i].replace(/<\/*span>/g, '');
                    }
                }
                console.log('Error edit clicked');
                // THE ERROR WINDOW
                var previousContent = $('#StatusModal div.modal-body').html();
                var newContent = [
                    '<h3>Name of the listing<span class="edit"><a href="#" class="btnCancel">Cancel</a></span></h3>',
                    '<div class="row">',
                    '<h5>Installation Status<span>Problem<input id="cbInstallation" type="checkbox"' + ((errors.installationErrors.length > 0) ? ' checked="checked"' : '') + '/></span></h5>',
                    '<label id="labelInstallation"' + ((errors.installationErrors.length === 0) ? ' class="ok"' : '') + '>Installation Problem:</label>',
                    '<input id="inputInstallation" type="text" value="' + errors.installationErrors.join(' ') + '"' + ((errors.installationErrors.length === 0) ? ' style="display: none; "' : '') + '/>',
                    '<h5>Lighting Status<span>Problem<input id="cbLighting" type="checkbox"' + ((errors.lightingErrors.length > 0) ? ' checked="checked"' : '') + '/></span></h5>',
                    '<label id="labelLighting"' + ((errors.lightingErrors.length === 0) ? ' class="ok"' : '') + '>Lighting Problem:</label>',
                    '<input id="inputLighting" type="text" value="' + errors.lightingErrors.join(' ') + '"' + ((errors.lightingErrors.length === 0) ? ' style="display: none; "' : '') + '/>',
                    '<h5>Obstruction Status<span>Problem<input id="cbObstruction" type="checkbox"' + ((errors.obstructionErrors.length > 0) ? ' checked="checked"' : '') + '/></span></h5>',
                    '<label id="labelObstruction"' + ((errors.obstructionErrors.length === 0) ? ' class="ok"' : '') + '>Obstruction Problem:</label>',
                    '<input id="inputObstruction" type="text" value="' + errors.obstructionErrors.join(' ') + '"' + ((errors.obstructionErrors.length === 0) ? ' style="display: none; "' : '') + '/>',
                    '</div>',
                    '<div class="row"><button class="btn btn-save">Save</button></div>'
                ].join('');
                $('#StatusModal div.modal-body').html(newContent);
                $('#StatusModal input[type="checkbox"]').fancyfields();
                // Event handler for the checkboxes (show / hide the corresponding input field)
                $('#StatusModal input[type="checkbox"]').fancyfields("bind", "onCheckboxChange", function (input, isChecked) {
                    var inputId = input.attr('id').replace('cb', 'input');
                    var labelId = input.attr('id').replace('cb', 'label');
                    if (isChecked === true) {
                        $('input#' + inputId).css('display', '');
                        $('label#' + labelId).removeClass('ok');
                    } else {
                        $('input#' + inputId).css('display', 'none').val('');
                        $('label#' + labelId).addClass('ok');
                    }
                });
                // Event handler for the cancel button
                $('#StatusModal div.modal-body a.btnCancel').click(function (e) {
                    console.log('Error cancel clicked');
                    $('#StatusModal div.modal-body').html(previousContent);
                });
                // Event handler for the save button
                $('#StatusModal button.btn-save').click(function (e) {
                    e.preventDefault();
                    var installationStatus = ($('input#inputInstallation').val() === '') ? false : true;
                    var installationProblemDetails = ($('input#inputInstallation').val() === '') ? '' : $('input#inputInstallation').val();
                    var lightingStatus =  ($('input#inputLighting').val() === '') ? false : true;
                    var lightingProblemDetails = ($('input#inputLighting').val() === '') ? '' : $('input#inputLighting').val();
                    var obstructionStatus =  ($('input#inputObstruction').val() === '') ? false : true;
                    var obstructionProblemDetails = ($('input#inputObstruction').val() === '') ? '' : $('input#inputObstruction').val();
                    Monitorly.getProofsForCampaignAndSite(campaignId, id, function (proofs) {
                        var proof,
                            proofId;
                        if (proofs.length === 0) {
                            console.log("Entirely new proof");
                            proof = new Proof();
                            proof.save({
                                campaign: {
                                    __type: 'Pointer',
                                    className: 'Campaign',
                                    objectId: campaignId
                                },
                                site: {
                                    __type: 'Pointer',
                                    className: 'Site',
                                    objectId: id
                                },
                                creator: Parse.User.current(),
                                installationStatus: installationStatus,
                                installationProblemDetails: installationProblemDetails,
                                lightingStatus: lightingStatus,
                                lightingProblemDetails: lightingProblemDetails,
                                obstructionStatus: obstructionStatus,
                                obstructionProblemDetails: obstructionProblemDetails
                            }).then(function (obj) {
                                // Close the modal box
                                location.reload(true);
                            });
                        } else {
                            proof = proofs[0];
                            proofId = proof.id;
                            console.log(proofId);
                            proof = new Proof();
                            proof.id = proofId;
                            proof.set('installationStatus', installationStatus);
                            proof.set('installationProblemDetails', installationProblemDetails);
                            proof.set('lightingStatus', lightingStatus);
                            proof.set('lightingProblemDetails', lightingProblemDetails);
                            proof.set('obstructionStatus', obstructionStatus);
                            proof.set('obstructionProblemDetails', obstructionProblemDetails);
                            proof.save(null, {
                                success: function () {
                                    location.reload(true);
                                }
                            });
                        }
                    });
                });
            });
        });
        $('div#sites').append(siteContainer);
    }

    function refreshSiteContainers() {
        var i, j, found, count = 0;
        for (i = 0; i < allSitesIds.length; i += 1) {
            found = false;
            for (j = 0; j < addedIds.length; j += 1) {
                if (addedIds[j] === allSitesIds[i]) {
                    found = true;
                    break;
                }
            }
            if (found === true) {
                $('div#site-' + allSitesIds[i])
                    .addClass('moved')
                    .draggable('disable');
                count += 1;
            } else {
                $('div#site-' + allSitesIds[i])
                    .removeClass('moved')
                    .draggable('enable');
            }
        }
        $('h1:first').find('span:first').html(count + ' sites');
    }

    function createSitesSiteContainer(currentSite) {
        var siteLink = '#',
            siteImage = currentSite.get('image').url(),
            siteContainer = $('<div></div>')
                .addClass('mosaicview')
                .html('<h3>' + currentSite.get('nameString') + '</h3><img src="' + siteImage + '" />')
                .appendTo($('div#centersitelist'));
        siteContainer.click(function (e) {
            e.preventDefault();
            var gps = currentSite.get('Gps');
            var lat = gps._latitude;
            var lng = gps._longitude;
            showModalDialog('StatusModal', 'login', [
                '<ul>',
                '<li><img src="' + currentSite.get('image').url() + '" /></li>',
                '<li><strong>Gps</strong>: ' + lat + ', ' + lng + '</li>',
                '<li><strong>siteId</strong>: ' + currentSite.get('siteId') + '</li>',
                '<li><strong>nameString</strong>: ' + currentSite.get('nameString') + '</li>',
                '<li><strong>dimensionsWidthIntegar</strong>: ' + currentSite.get('dimensionsWidthIntegar') + '</li>',
                '<li><strong>dimensionsHeightIntegar</strong>: ' + currentSite.get('dimensionsHeightIntegar') + '</li>',
                '<li><strong>descrptionString</strong>: ' + currentSite.get('descrptionString') + '</li>',
                '<li><strong>unit_ftOrMString</strong>: ' + currentSite.get('unit_ftOrMString') + '</li>',
                '</ul>'
            ].join(''));
        });
    }

    function pad(number) {
        if (number <= 10) {
            number = ('0' + number).slice(-4);
        }
        return '' + number;
    }

    function createCaptureContainer(proof) {
        var captureContainer,
            imgUrl = (proof.get('imageFile')) ? proof.get('imageFile').url() : proof.get('site').get('image').url(),
            proofDate = '',
            proofTime = '',
            createdAt,
            currentSite = proof.get('site'),
            widthString = currentSite.get('dimensionsWidthIntegar') + ' ' + currentSite.get('unit_ftOrMString'),
            heightString = currentSite.get('dimensionsHeightIntegar') + ' ' + currentSite.get('unit_ftOrMString');
        createdAt = proof.createdAt;
        proofDate = createdAt.getDate() + '-' + ((createdAt.getMonth() < 10) ? '0' + (createdAt.getMonth() + 1) : (createdAt.getMonth() + 1)) + '-' + createdAt.getFullYear();
        proofTime = pad(createdAt.getHours()) + ':' + pad(createdAt.getMinutes()) + ':' + pad(createdAt.getSeconds());
        captureContainer = $('<div></div>')
            .addClass('item')
            .html('<a href="#"><img src="' + imgUrl + '" /><span class="date">' + proofDate + '</span><span class="time"> ' + proofTime + '</span></a>')
            .appendTo('div.main div.right div.row');
        // Click will display the site...
        captureContainer.click(function (e) {
            var mapOptions = {
                zoom: 8,
                center: new google.maps.LatLng(currentSite.get('Gps')._latitude, currentSite.get('Gps')._longitude)
            };
            e.preventDefault();
            map = undefined;
            var description = (currentSite.get('descriptionString') ? currentSite.get('descriptionString') : '');
            showModalDialog('StatusModal', 'login', [
                '<h3>' + currentSite.get('nameString') + '</h3>',
                '<div class="left">',
                '<img src="' + currentSite.get('image').url() + '">',
                '<p><strong>Desription</strong><br/>' + description + '</p>',
                '</div>',
                '<div class="right">',
                '<div id="map-canvas"></div>',
                '<p><strong>Dimensions:</strong><br/>',
                'Width: ' + widthString + '<br/>Height: ' + heightString + '</p>',
                '</div>'
            ].join(''));
            if (!map) {
                map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            }
        });

    }

    // DOM ready
    $(function() {
        var page = $('input#page_name:first').val();
        // Generate report button
        $('button.btn-createreport').click(function (e) {
            showModalDialog('Generate', 'login', [
                '<h3>Generate a report</h3>',
                '<div class="row">',
                '<label><input type="radio" value="errors" name="reporttype"> include only errors</label>',
                '<label><input type="radio" value="ok" name="reporttype"> include only non problematic listings</label>',
                '<label><input type="radio" value="all" name="reporttype" checked="checked"> include all</label>',
                '</div>',
                '<div class="row">',
                '<button class="btn btn-success generate" id="pdf">Download PDF</button>',
                '<button class="btn btn-success generate" id="longlink">Generate Link</button>',
                '<a href="#" class="btnCancelReport">Cancel</a>',
                '</div>'
            ].join(''));
            $('#Generate select, #Generate input[type="radio"]').fancyfields();
            $('button#pdf').click(function (e) {
                e.preventDefault();
                // Long link
                var link = 'index.php?page=report-pdf&id=' + $('input#campaign_id:first').val() + '&type=' + $('input:radio[name=reporttype]:checked').val();
                window.location.href = link;
            });
            $('button#longlink').click(function (e) {
                e.preventDefault();
                // Long link
                var link = 'index.php?page=report&id=' + $('input#campaign_id:first').val() + '&type=' + $('input:radio[name=reporttype]:checked').val();
                window.location.href = link;
            });
            $('a.btnCancelReport').click(function (e) {
                e.preventDefault();
                $('#Generate').modal('hide');
            });
        });
        // Logout button
        $('button#btnLogout').click(function (e) {
            e.preventDefault();
            Monitorly.logout(function () {
                window.location.href = 'login.php';
            });
        });
        // Save button
        $('button#btnSave').click(function (e) {
            window.location.href = 'index.php?page=campaign&id=' + $('input#campaign_id:first').val();
        });
        // Drag and drop editing
        $('div.left.dnd:first').droppable({
            accept: 'div#draggable div.mosaicview',
            drop: function (event, ui) {
                var siteContainer = ui.draggable,
                    id = siteContainer.attr('id').replace(/site-/, ''),
                    name = siteContainer.find('h3:first').html(),
                    img = siteContainer.find('img:first').attr('src');
                ui.draggable.draggable('disable');
                ui.draggable.draggable( 'option', 'revert', false );
                createAddedSiteContainer(id, name, [], img);
            }
        });
        // Init monitorly stuff
        Monitorly.init();
        if (Parse.User.current()) {



            if ((page === 'report') || (page === 'report-pdf')) {
                Monitorly.getCampaign($('input[type=hidden]#campaign_id:first').val(), function (campaign) {
                    var sites = campaign.get('Sites'),
                        length = (typeof sites !== 'undefined') ? sites.length : 0,
                        i,
                        currentSite,
                        numErrors = 0;
                    myCampaign = campaign;
                    $('h1:first').html(campaign.get('Name') + ' <span>' + length + ' sites</span>');
                    $('p#campaign_desc').html(campaign.get('description'));
                    // Loop through all the sites for this campaign
                    function newSite(site) {
                        var img = site.get('image').url();
                        // Check the proofs table too
                        var OK_BOOL = false;
                        Monitorly.getProofsForCampaignAndSite(campaign.id, site.id, function (proofs) {
                            var numberOfProofs = proofs.length,
                                installationStatus = OK_BOOL,
                                installationProblemDetails = '',
                                lightingStatus = OK_BOOL,
                                lightingProblemDetails = '',
                                obstructionStatus = OK_BOOL,
                                obstructionProblemDetails = '',
                                currentProof,
                                installationErrors = [],
                                lightingErrors = [],
                                obstructionErrors = [],
                                reporter = '',
                                reportedAt = '',
                                createdAt;
                            for (i = 0; i < numberOfProofs; i++) {
                                installationErrors = [];
                                lightingErrors = [];
                                obstructionErrors = [];
                                currentProof = proofs[i];
                                // Installation status
                                reporter = currentProof.get('creator').get('username');
                                createdAt = currentProof.createdAt;
                                reportedAt = createdAt.getDate() + '-' + ((createdAt.getMonth() < 10) ? '0' + (createdAt.getMonth() + 1) : (createdAt.getMonth() + 1)) + '-' + createdAt.getFullYear();
                                installationStatus = currentProof.get('installationStatus');
                                if ((installationStatus === OK_BOOL) || (typeof installationStatus === 'undefined')) {
                                    installationProblemDetails = '';
                                } else {
                                    installationProblemDetails = currentProof.get('installationProblemDetails');
                                    installationErrors.push(installationProblemDetails);
                                }
                                // Lighting status
                                lightingStatus = currentProof.get('lightingStatus');
                                if ((lightingStatus === OK_BOOL) || (typeof lightingStatus === 'undefined')) {
                                    lightingProblemDetails = '';
                                } else {
                                    lightingProblemDetails = currentProof.get('lightingProblemDetails');
                                    lightingErrors.push(lightingProblemDetails);
                                }
                                // Obstruction status
                                obstructionStatus = currentProof.get('obstructionStatus');
                                if ((obstructionStatus === OK_BOOL) || (typeof obstructionStatus === 'undefined')) {
                                    obstructionProblemDetails = '';
                                } else {
                                    obstructionProblemDetails = currentProof.get('obstructionProblemDetails');
                                    obstructionErrors.push(obstructionProblemDetails);
                                }
                            }
                            createReportContainer(site.id, site.get('nameString'), {
                                installationErrors: installationErrors,
                                lightingErrors: lightingErrors,
                                obstructionErrors: obstructionErrors,
                                reporter: reporter,
                                reportedAt: reportedAt
                            }, img, $('input[type=hidden]#campaign_id:first').val(), page);
                        });
                    }
                    for (i = 0; i < length; i++) {
                        Monitorly.getSite(sites[i].id, newSite);
                    }
                    if (length === 0) {
                        $('div#sites').html('No sites have been added.');
                    }
                    if (page === 'report-pdf') {
                        setTimeout(function() {
                            $('div.right textarea').val($('div#reportitemlist').html());
                            $('div.right form:first').submit();
                        }, 2000);
                    }
                });
            }



            if (page === 'sites') {
                Monitorly.getSites(function (sites) {
                    var length = sites.length,
                        i,
                        currentSite,
                        siteLink,
                        siteImage;
                    // Create the big containers too
                    function displaySiteDetails(e) {
                        e.preventDefault();
                    }
                    for (i = 0; i < length; i++) {
                        currentSite = sites.at(i);
                        createSitesSiteContainer(currentSite);
                    }
                });
            }



            if (page === 'campaign') {
                $('select').fancyfields({
                    onSelectChange: function (input, text, value) {
                        var $allSiteContainers = $('div#sites div.mosaicview');
                        if (value === 'all') {
                            $allSiteContainers.each(function (index) {
                                $(this).css('display', '');
                            });
                        } else if (value === 'errors') {
                            $allSiteContainers.each(function (index) {
                                if (!$(this).hasClass('error')) {
                                    $(this).css('display', 'none');
                                } else {
                                    $(this).css('display', '');
                                }
                            });
                        } else {
                            // Display only the ones that are OK
                            $allSiteContainers.each(function (index) {
                                if (!$(this).hasClass('error')) {
                                    $(this).css('display', '');
                                } else {
                                    $(this).css('display', 'none');
                                }
                            });
                        }
                    }
                });
                Monitorly.getCampaign($('input[type=hidden]#campaign_id:first').val(), function (campaign) {
                    var sites = campaign.get('Sites'),
                        length = (typeof sites !== 'undefined') ? sites.length : 0,
                        i,
                        currentSite,
                        numErrors = 0;
                    myCampaign = campaign;
                    $('h1:first').html(campaign.get('Name') + ' <span>' + length + ' sites</span>');
                    $('p#campaign_desc').html(campaign.get('description'));
                    // Loop through all the sites for this campaign
                    function newSite(site) {
                        var img = site.get('image').url();
                        // Check the proofs table too
                        var OK_BOOL = false;
                        Monitorly.getProofsForCampaignAndSite(campaign.id, site.id, function (proofs) {
                            var numberOfProofs = proofs.length,
                                installationStatus = OK_BOOL,
                                installationProblemDetails = '',
                                lightingStatus = OK_BOOL,
                                lightingProblemDetails = '',
                                obstructionStatus = OK_BOOL,
                                obstructionProblemDetails = '',
                                currentProof,
                                installationErrors = [],
                                lightingErrors = [],
                                obstructionErrors = [],
                                reporter = '',
                                reportedAt = '',
                                createdAt;
                            for (i = 0; i < numberOfProofs; i++) {
                                installationErrors = [];
                                lightingErrors = [];
                                obstructionErrors = [];
                                currentProof = proofs[i];
                                // Installation status
                                reporter = currentProof.get('creator').get('username');
                                createdAt = currentProof.createdAt;
                                reportedAt = createdAt.getDate() + '-' + ((createdAt.getMonth() < 10) ? '0' + (createdAt.getMonth() + 1) : (createdAt.getMonth() + 1)) + '-' + createdAt.getFullYear();
                                installationStatus = currentProof.get('installationStatus');
                                if ((installationStatus === OK_BOOL) || (typeof installationStatus === 'undefined')) {
                                    installationProblemDetails = '';
                                } else {
                                    installationProblemDetails = currentProof.get('installationProblemDetails');
                                    installationErrors.push(installationProblemDetails);
                                }
                                // Lighting status
                                lightingStatus = currentProof.get('lightingStatus');
                                if ((lightingStatus === OK_BOOL) || (typeof lightingStatus === 'undefined')) {
                                    lightingProblemDetails = '';
                                } else {
                                    lightingProblemDetails = currentProof.get('lightingProblemDetails');
                                    lightingErrors.push(lightingProblemDetails);
                                }
                                // Obstruction status
                                obstructionStatus = currentProof.get('obstructionStatus');
                                if ((obstructionStatus === OK_BOOL) || (typeof obstructionStatus === 'undefined')) {
                                    obstructionProblemDetails = '';
                                } else {
                                    obstructionProblemDetails = currentProof.get('obstructionProblemDetails');
                                    obstructionErrors.push(obstructionProblemDetails);
                                }
                            }
                            createSiteContainer(site.id, site.get('nameString'), {
                                installationErrors: installationErrors,
                                lightingErrors: lightingErrors,
                                obstructionErrors: obstructionErrors,
                                reporter: reporter,
                                reportedAt: reportedAt
                            }, img, $('input[type=hidden]#campaign_id:first').val());
                        });
                    }
                    for (i = 0; i < length; i++) {
                        Monitorly.getSite(sites[i].id, newSite);
                    }
                    if (length === 0) {
                        $('div#sites').html('No sites have been added.');
                    }
                });
            }



            if (page === 'captures') {
                var query = new Parse.Query(Proof);
                query.include('creator');
                query.include('site');
                query.find({
                    success: function (proofs) {
                        var i,
                            currentProof,
                            length;
                        if (proofs) {
                            length = proofs.length;
                        }
                        if (length > 0) {
                            for (i = 0; i < proofs.length; i += 1) {
                                createCaptureContainer(proofs[i]);
                            }
                        }
                    },
                    error: function (object, error) {
                        console.log(error);
                    }
                });
            }



            if (page === 'campaign_edit') {
                Monitorly.getCampaign($('input[type=hidden]#campaign_id:first').val(), function (campaign) {
                    var sites = campaign.get('Sites'),
                        length = (typeof sites !== 'undefined') ? sites.length : 0,
                        i,
                        currentSite,
                        numErrors = 0;
                    myCampaign = campaign;
                    $('h1:first').html(campaign.get('Name') + ' <span>' + length + ' sites</span>');
                    // Loop through all the sites for this campaign
                    function newSite(site) {
                        createAddedSiteContainer(site.id, site.get('nameString'), [], site.get('image').url());
                    }
                    for (i = 0; i < length; i++) {
                        Monitorly.getSite(sites[i].id, newSite);
                    }
                });
                Monitorly.getSites(function (sites) {
                    var length = sites.length,
                        i = 0,
                        currentSite;
                    for (i = 0; i < length; i += 1) {
                        currentSite = sites.at(i);
                        createDraggableSiteContainer(currentSite.id, currentSite.get('nameString'), currentSite.get('image').url());
                    }
                });
            }



            Monitorly.getCampaigns(function (campaigns) {
                // Go through all these collections and display them
                var length = campaigns.length,
                    i,
                    currentCampaign,
                    campaignLink,
                    active = false,
                    activeClass = '';
                for (i = length - 1; i >= 0; i--) {
                    currentCampaign = campaigns.at(i);
                    campaignLink = 'index.php?page=campaign&id=' + currentCampaign.id;
                    if ($('input[type=hidden]#campaign_id')) {
                        if ($('input[type=hidden]#campaign_id:first').val() === currentCampaign.id) {
                            active = true;
                        } else {
                            active = false;
                        }
                    }
                    if (page == 'index') {
                        // Add this campaign to the folder list
                        // Create DOM object
                        $('<div><div>')
                            .addClass('item')
                            .html('<a href="' + campaignLink + '"><img src="img/folder.png" /><span>' + currentCampaign.get('Name') + '<span></a>')
                            .prependTo('div.right > div.row');
                    }
                    // Add the campaign to the sidebar list too
                    if (active === true) {
                        activeClass = ' active';
                    } else {
                        activeClass = '';
                    }
                    $('<li></li>')
                        .html('<a href="' + campaignLink + '">' + currentCampaign.get('Name') + '</a>')
                        .addClass(activeClass)
                        .prependTo('div.container.main ul:first');
                }
            });
        } else {
            // Redirect to login
            window.location.href = 'login.php';
        }
    });
})(jQuery, document);

