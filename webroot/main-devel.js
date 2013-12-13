/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU AFFERO General Public License as published by
the Free Software Foundation; either version 3 of the License, or
any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
or see http://www.gnu.org/licenses/agpl.txt.
*/

// in the upper right corner of the map
L.Control.Status = L.Control.Attribution.extend({
    options: {
        position: 'topright',
        tpl: null
    },
    initialize: function(options) {
        L.Util.setOptions(this, options);
    },
    onAdd: function(map){
        this._container = L.DomUtil.create('div', 'leaflet-control-status');
        this._map = map;
        this._attributions = {};
        this._update();
        return this._container;
    },
    setText: function(text){
        this.options.tpl.overwrite(this._container,[text]);
    }
});

// Extend L.Marker to set some default mouseover and mouseout events
L.OsmMarker = L.Marker.extend({
    options: {
        mouseoverText: Ext.ux.ts.tr('Drag to change route or double click to remove'),
        mouseoutText: '',
        draggable: true,
        ctrl: null
    },
    initialize: function(latlng, options) {
        L.Util.setOptions(this, options);
        this._latlng = latlng;
        this.on('mouseover', function(evt){
            this.options.ctrl.setText(this.options.mouseoverText);
        });
        this.on('mouseout', function(evt){
            this.options.ctrl.setText(this.options.mouseoutText);
        });
    },
    onRemove: function(map) {
        L.Marker.prototype.onRemove.call(this, map);
        this.options.ctrl.setText(this.options.mouseoutText);
    }
});

L.StartIcon = L.Icon.extend({
    options: {
        iconAnchor: new L.Point(12, 41),
        iconSize: new L.Point(25, 41),
        iconUrl: '/img/startmarker.png',
        shadowUrl: '/lib/leaflet-0.4.4/dist/images/marker-shadow.png'
    }
});

L.EndIcon = L.Icon.extend({
    options: {
        iconAnchor: new L.Point(12, 41),
        iconSize: new L.Point(25, 41),
        iconUrl: '/img/endmarker.png',
        shadowUrl: '/lib/leaflet-0.4.4/dist/images/marker-shadow.png'
    }
});

L.ViaIcon = L.Icon.extend({
    options: {
        iconUrl: '/img/viamarker.png',
        shadowUrl: '/img/viamarker-shadow.png',
        iconSize: new L.Point(12, 12),
        shadowSize: new L.Point(12, 12),
        iconAnchor: new L.Point(6, 6),
        popupAnchor: new L.Point(-6, -6)
    }
});

/*!
 * Ext JS Library 3.1.1
 * Copyright(c) 2006-2010 Ext JS, LLC
 * licensing@extjs.com
 * http://www.extjs.com/license
 */
Ext.onReady(function(){

    // The start marker
    var startmarker = null;
    // The end marker
    var endmarker = null;
    // The via markers
    var viamarkers = new Array();
    // The place marker
    var placemarker = null;
    // The result route
    var route = null;
    var currentLang = initLang;

    // Inital map type is "map"
    var mapType = "map";

    // General tempatles:
    // Template to keep the edit link on the homepage syncronized with the map
    var editTmpText = "<a class=\"external\" href=\"http://www.openstreetmap.org/edit?lat={lat}&lon={lon}&zoom={zoom}\">" + Ext.ux.ts.tr('edit the map') + " </a>";
    var editTemplate = new Ext.Template(editTmpText);
    editTemplate.compile();
    // Create the template that is used in the status control
    var statusTpl = Ext.DomHelper.createTemplate({
        html: '{0}',
        tag: 'div'
    });
    statusTpl.compile();

    // General Ajax events that gives feedback to the user
    // while requesting the server
    Ext.Ajax.on('beforerequest', function(evt){
        statusControl.setText(Ext.ux.ts.tr('Loading...'));
    }, this);
    Ext.Ajax.on('requestcomplete', function(evt){
        statusControl.setText('');
    }, this);


    // The attribution string with hyperlinks
    var attribution = 'map &copy; <a href=\"http://www.osm-tools.org\">osm-tools.org</a>,\n\
            data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors';

    // Define the English OpenStreetMap layer
    var osmtoolsEnUrl = 'http://{s}.laostile.osm-tools.org/osm_en/{z}/{x}/{y}.png';
    var osmEnLayer = new L.TileLayer(osmtoolsEnUrl, {
        attribution: attribution,
        minZoom: 5,
        maxZoom: 18
    });

    // Define the Lao OpenStreetMap layer
    var osmtoolsLoUrl = 'http://{s}.laostile.osm-tools.org/osm_th/{z}/{x}/{y}.png';
    var osmLoLayer = new L.TileLayer(osmtoolsLoUrl, {
        attribution: attribution,
        minZoom: 5,
        maxZoom: 18
    });

    var satelliteUrl = '/tms/1.0.0/landsat8/{z}/{x}/{y}.jpeg';
    var satelliteLayer = new L.TileLayer(satelliteUrl, {
        attribution: 'Landsat 8 <a href="http://eros.usgs.gov/#/About_Us/Customer_Service/Data_Citation">imagery available from the U.S. Geological Survey</a>,\n\
            data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',
        minZoom: 5,
        maxZoom: 14,
        tms: true
    });

    var layers = {
        map: {
            en: osmEnLayer,
            lo: osmLoLayer
        },
        satellite: {
            en: satelliteLayer,
            lo: satelliteLayer
        }
    }
    Ext.encode(layers);
    //console.log(layers);
    //console.log(layers[currentLang]);

    // Create the new leaflet map and add the Lao map
    var map = new L.Map('map');
    map.addLayer(layers[mapType][currentLang]);
    map.attributionControl.setPrefix('');

    // Create the status control and reset the text
    var statusControl = new L.Control.Status({
        tpl: statusTpl
    });
    map.addControl(statusControl);
    statusControl.setText('');

    var searchComboBox = new Ext.ux.LocationComboBox({
        listeners: {
            'select': function(combo, record, index){
                // Get the coordinates from the selected record
                var c = Fgh.decode(record.data.hash);
                var coords = new L.LatLng(c.lat, c.lon);
                addPlaceMarker(coords, true);
            }
        },
        map: map,
        width: 325
    });

    // Create an adapted toolbar for the map panel
    var mapToolbar = new Ext.ux.LanguageToolbar({
        currentLang: currentLang,
        statusControl: statusControl,
        url: '/'
    });

    var mapButton = new Ext.Button({
        handler: function(button){
            map.removeLayer(layers[mapType][currentLang]);
            mapType = "map";
            map.addLayer(layers[mapType][currentLang]);
        },
        iconCls: 'map-button',
        iconAlign: 'top',
        listeners: {
            mouseover: function(button, evt){
                statusControl.setText(Ext.ux.ts.tr('Map'));
            },
            mouseout: function(button, evt){
                statusControl.setText('');
            }
        },
        pressed: mapType == "map",
        style: {
            padding: '0px 2px 0px'
        },
        tooltip: Ext.ux.ts.tr("Map"),
        toggleGroup: "maptype"
    });

    var satelliteButton = new Ext.Button({
        handler: function(button){
            map.removeLayer(layers[mapType][currentLang]);
            mapType = "satellite";
            map.addLayer(layers[mapType][currentLang]);
        },
        iconCls: 'satellite-button',
        iconAlign: 'top',
        listeners: {
            mouseover: function(button, evt){
                statusControl.setText(Ext.ux.ts.tr('Satellite'));
            },
            mouseout: function(button, evt){
                statusControl.setText('');
            }
        },
        pressed: mapType == "satellite",
        style: {
            padding: '0px 2px 0px'
        },
        tooltip: Ext.ux.ts.tr("Satellite"),
        toggleGroup: "maptype"
    });

    // Add a separator
    mapToolbar.insertButton(1, '-');

    // Insert the permanent link button
    mapToolbar.insertButton(1,{
        cls:'x-btn-text-icon',
        handler: function(evt){
            var url = extractPermalink(currentLang);
            window.location.href = url;
        },
        //icon: '/img/actionPermaLink.png',
        iconCls: 'link-button',
        iconAlign: 'right',
        listeners: {
            mouseover: function(button, evt){
                statusControl.setText(Ext.ux.ts.tr('Permanent link to current map view'));
            },
            mouseout: function(button, evt){
                statusControl.setText('');
            }
        }
    //text: Ext.ux.ts.tr('Link')
    });

    mapToolbar.insertButton(1, '-');

    mapToolbar.insertButton(1, mapButton);

    mapToolbar.insertButton(1, satelliteButton);

    // Add the location search combo box
    mapToolbar.insert(0, searchComboBox);

    // The map panel, set the center and zoom level
    var mapPanel = new Ext.ux.MapPanel({
        center: initCenter,
        contentEl: 'map',
        editEl: 'edit-link',
        editTpl: editTemplate,
        id: 'map-tabpanel',
        map: map,
        tbar: mapToolbar,
        title: Ext.ux.ts.tr('Map'),
        zoom: initZoom
    });

    var mainTabPanel = new Ext.TabPanel({
        activeTab: 'map-tabpanel',
        items: [
        mapPanel,
        {
            autoScroll: true,
            contentEl: 'edit-tab',
            id: 'edit-tabpanel',
            title: Ext.ux.ts.tr('Edit'),
            tbar: new Ext.ux.LanguageToolbar({
                currentLang: currentLang,
                statusControl: statusControl,
                url: '/edit/'
            }),
            xtype: 'panel'
        },
        {
            autoScroll: true,
            contentEl: 'downloads-tab',
            id: 'downloads-tabpanel',
            title: Ext.ux.ts.tr('Downloads'),
            tbar: new Ext.ux.LanguageToolbar({
                currentLang: currentLang,
                statusControl: statusControl,
                url: '/downloads/'
            }),
            xtype: 'panel'
        },{
            autoScroll: true,
            contentEl: 'about-tab',
            id: 'about-tabpanel',
            title: Ext.ux.ts.tr('About this page'),
            tbar: new Ext.ux.LanguageToolbar({
                currentLang: currentLang,
                statusControl: statusControl,
                url: '/about/'
            }),
            xtype: 'panel'
        }],
        listeners: {
            afterrender: function(component){
                component.setActiveTab(Ext.ux.activeTab + '-tabpanel');
            }
        },
        region: 'center'
    });

    // The first from location combo box
    var startCombo = new Ext.ux.LocationComboBox({
        emptyText: Ext.ux.ts.tr('Search village, point of interest, etc.'),
        listeners: {
            'select': function(combo, record, index){
                // Get the coordinates from the selected record
                var c = Fgh.decode(record.data.hash);
                // Project the coordinates
                var coords = new L.LatLng(c.lat, c.lon);
                addStartMarker(coords, true);
            }
        },
        map: map
    });

    // The end combobox
    var endCombo = new Ext.ux.LocationComboBox({
        emptyText: Ext.ux.ts.tr('Search village, point of interest, etc.'),
        listeners: {
            'select': function(combo, record, index){
                // Get the coordinates from the selected record
                var c = Fgh.decode(record.data.hash);
                // Project the coordinates
                var coords = new L.LatLng(c.lat, c.lon);
                addEndMarker(coords, true);
            },
            scope: this
        },
        map: map
    });

    // Create a shorthand alias
    var dh = Ext.DomHelper;

    // Create the template for a row in the route instructions table
    var routeInstructionsTpl = dh.createTemplate({
        tag: 'tr', 
        id: 'row{0}',
        children: [{
            tag: 'td',
            html: '{0}.',
            cls: '{1}'
        },{
            tag: 'td',
            html: '{2} {3}',
            cls: '{1}'
        },{
            tag: 'td',
            html: '{4}',
            cls: '{1}'
        }]
    });

    // Create the template to show the route distance and time summary
    var routeSummaryTpl = dh.createTemplate({
        tag: 'div',
        html: Ext.ux.ts.tr('Distance') + ': <b>' + Ext.ux.ts.tr('{0} km')
        + '</b><br/>' + Ext.ux.ts.tr('Duration') + ': <b>' + Ext.ux.ts.tr('{1} h {2} mins') + '</b>'
    });

    var routeResultPanel = new Ext.Panel({
        autoScroll: true,
        region: 'center',
        contentEl: 'route-result-panel',
        id: 'routingpanel',
        baseCls: 'x-panel',
        ctCls: 'x-panel-mc'
    });

    // The left side panel containing the logo and the direction fields
    var sidePanel = new Ext.Panel({
        items: [{
            align: 'stretch',
            height: 350,
            items: [
            {
                baseCls: 'x-panel',
                contentEl: 'sidepanel-header',
                ctCls: 'x-panel-mc',
                width: 250,
                xtype: 'panel'
            },{
                bodyStyle:'padding: 10px 0px 0px 10px',
                buttonAlign: 'right',
                defaults: {
                    width: 230
                },
                hideLabels: true,
                items: [{
                    id: 'get-directions-header',
                    html: '<h1>' + Ext.ux.ts.tr('Get directions') + '</h1>',
                    xtype: 'label'
                },{
                    hideLabel: true,
                    items: [startCombo, {
                        handler: function(btn, evt){
                            // Add the startmarker to the center of the map
                            addStartMarker(map.getCenter(), false);
                            // and update the start combobox
                            startCombo.setLocationValue(map.getCenter());
                        },
                        iconCls: 'startmarker',
                        tooltip: Ext.ux.ts.tr('Add start'),
                        tooltipType: 'title',
                        xtype: 'button'
                    }],
                    width: 'auto',
                    xtype: 'compositefield'
                },{
                    hideLabel: true,
                    items: [endCombo,{
                        // Add the endmarker to the center of the map
                        handler: function(btn, evt){
                            // Add the endmarker to the center of the map
                            addEndMarker(map.getCenter(), false);
                            // and update the end combobox
                            endCombo.setLocationValue(map.getCenter());
                        },
                        iconCls: 'endmarker',
                        tooltip: Ext.ux.ts.tr('Add destination'),
                        tooltipType: 'title',
                        xtype: 'button'
                    }],
                    width: 'auto',
                    xtype: 'compositefield'
                },{
                    handler: function(btn, evt) {
                        clearStartMarker();
                        clearEndMarker();
                        // Remove also all via markers
                        for(var i = 0; i < viamarkers.length; i++){
                            map.removeLayer(viamarkers[i]);
                            map.closePopup();
                        }
                        viamarkers = new Array();
                        startCombo.clearValue();
                        endCombo.clearValue();
                        doRouting(false);
                    },
                    text: Ext.ux.ts.tr('Clear'),
                    width: 100,
                    xtype: 'button'
                }],
                width: 250,
                xtype: 'form'
            },{
                baseCls: 'x-panel',
                contentEl: 'route-summary-panel',
                ctCls: 'x-panel-mc',
                width: 250,
                xtype: 'panel'
            }
            ],
            layout: 'vbox',
            region: 'north',
            xtype: 'panel'
        },
        routeResultPanel
        ],
        layout: 'border',
        region: 'west',
        width: 250
    });

    var mainViewport = new Ext.Viewport({
        layout: "border",
        items: [
        sidePanel,
        mainTabPanel
        ]
    });

    // Tender the grid to the specified div in the page
    mainViewport.render('main');

    // Add routing start and end marker to the map if set in the URL request
    if(initStart){
        addStartMarker(initStart, false);
        startCombo.setLocationValue(initStart);
    }

    if(initDest){
        addEndMarker(initDest, false);
        endCombo.setLocationValue(initDest);
    }

    if(initVias){
        for(var i = 0; i < initVias.length; i++){
            addViaMarker(initVias[i]);
        }
    }

    // Implement the start URL request if a marker was set
    if(initMarker) {
        addPlaceMarker(initMarker, false);
        searchComboBox.setLocationValue(placemarker.getLatLng());
    }

    /**
     * Extracts map center, current placemarker and routing (if any) and language
     * from the current map view and returns a permalink.
     * @param language
     * @type String
     */
    function extractPermalink(language){
        // Get the center and the zoom of the current map position
        var c = map.getCenter();
        var z = map.getZoom();
        // Check if the placemarker is set or not, if yes, add it also
        // to the permalink URL
        var mLatLng = "";
        if(placemarker){
            var ll = placemarker.getLatLng();
            mLatLng = "&mlat=" + ll.lat + "&mlon=" + ll.lng;
        }

        // Check if start and end marker are set
        var startHash = "";
        if(startmarker){
            var startLatLng = startmarker.getLatLng();
            var sh = Fgh.encode(startLatLng.lat, startLatLng.lng, 52);
            startHash = "&start=" + sh;
        }
        var destHash = "";
        if(endmarker){
            var destLatLng = endmarker.getLatLng();
            var eh = Fgh.encode(destLatLng.lat, destLatLng.lng, 52);
            destHash = "&dest=" + eh;
        }
        var viaHash = "";
        if(viamarkers.length > 0){
            viaHash += "&via=";
            for(var i = 0; i < viamarkers.length; i++){
                var viaLatLng = viamarkers[i].getLatLng();
                var vh = Fgh.encode(viaLatLng.lat, viaLatLng.lng, 52);
                viaHash += vh
                if(i < (viamarkers.length-1)){
                    viaHash += ",";
                }
            }
        }
        var lang = "&lang=" + language;
        return "/?lat=" + c.lat + "&lon=" + c.lng + "&zoom=" + z + mLatLng + startHash + destHash + viaHash + lang;
    }

    /**
     * @param latLng L.LatLng Position of tne marker
     * @param recenter Boolean
     */
    function addStartMarker(latLng, recenter){
        if(startmarker) {
            map.removeLayer(startmarker);
        }

        // Project the coordinates
        startmarker = new L.OsmMarker(latLng, {
            ctrl: statusControl,
            icon: new L.StartIcon()
        });
        startmarker.on('dblclick', function(evt){
            clearStartMarker();
            doRouting(false);
        });
        startmarker.on('dragend', function(evt){
            // Never recenter on dragend event
            doRouting(false);
            // Update the start combo field after dragend
            startCombo.setLocationValue(this.getLatLng());
        });
        map.addLayer(startmarker);
        // Don't zoom to the route when adding the marker
        // by button
        doRouting(recenter);
    }

    function clearStartMarker(){
        if(startmarker) {
            map.removeLayer(startmarker);
        }
        startmarker = null;
        startCombo.clearValue();
    }

    function addEndMarker(latLng, recenter){
        if(endmarker) {
            map.removeLayer(endmarker);
        }
        // Instantiate the end marker
        endmarker = new L.OsmMarker(latLng, {
            ctrl: statusControl,
            icon: new L.EndIcon()
        });
        endmarker.on('dblclick', function(evt){
            clearEndMarker();
            doRouting(false);
        });
        endmarker.on('dragend', function(evt){
            // Never recenter on dragend event
            doRouting(false);
            // Update the end combobox after dragend
            endCombo.setLocationValue(this.getLatLng());
        });
        map.addLayer(endmarker);
        // Don't zoom to the route when adding the marker
        // by button
        doRouting(recenter);
    }

    function clearEndMarker(){
        if(endmarker) {
            map.removeLayer(endmarker);
        }
        endmarker = null;
        endCombo.clearValue();
    }

    function addViaMarker(latLng){
        var viaMarker = new L.OsmMarker(latLng, {
            ctrl: statusControl,
            draggable: true,
            icon: new L.ViaIcon()
        });
        viaMarker.on('dragend', function(evt){
            doRouting(false);
        });
        viaMarker.on('dblclick', function(evt){
            viamarkers.remove(this);
            map.removeLayer(this);
            doRouting(false);
        });
        viamarkers.push(viaMarker);
        map.addLayer(viaMarker);
        doRouting(false);
    }

    function clearViaMarkers(){
        for(var i = 0; i < viamarkers.length; i++){
            map.removeLayer(viamarkers[i]);
        }
        viamarkers = new Array();
    }

    function addPlaceMarker(latLng, panTo){
        // First remove an existing place marker (if any)
        if(placemarker) {
            map.removeLayer(placemarker);
        }

        placemarker = new L.OsmMarker(latLng,{
            ctrl: statusControl,
            mouseoverText: Ext.ux.ts.tr('Double click to remove')
        });
        placemarker.on('dblclick', function(evt){
            clearPlaceMarker();
        });
        placemarker.on('dragend', function(evt){
            // Update the start combo field after dragend
            searchComboBox.setLocationValue(placemarker.getLatLng());
        });
        map.addLayer(placemarker);

        if(panTo) {
            map.panTo(latLng);
        }
    }

    function clearPlaceMarker() {
        if(placemarker){
            map.removeLayer(placemarker);
            placemarker = null;
        }
        searchComboBox.clearValue();
    }

    /**
     * Returns a comma separated string with geohashs
     * @type String
     */
    function getViaHashs(){
        var viaHashs = new Array();
        for(var i = 0; i < viamarkers.length; i++){
            viaHashs.push(Fgh.encode(viamarkers[i].getLatLng().lat, viamarkers[i].getLatLng().lng, 52));
        }
        return viaHashs.join(',');
    }

    function doRouting(recenter){
        // Check if the start marker and the end marker are NOT null
        if(startmarker && endmarker) {

            // Request the server asynchronously
            Ext.Ajax.request({
                failure: function(response){
                    var failureMsg = '<h2>' + Ext.ux.ts.tr('No route found')
                    + '</h2>' + Ext.ux.ts.tr('Server is currently unreachable or its answer is invalid.')
                    Ext.Msg.alert(Ext.ux.ts.tr('Not found'), failureMsg);
                },
                method: 'GET',
                params: {
                    start: Fgh.encode(startmarker.getLatLng().lat, startmarker.getLatLng().lng, 52),
                    dest: Fgh.encode(endmarker.getLatLng().lat, endmarker.getLatLng().lng, 52),
                    via: getViaHashs()
                },
                success: function(response){
                    // Remove first all existing features
                    if(route) {
                        map.removeLayer(route);
                    }

                    // Extract the route information from the response
                    var responseObj = Ext.decode(response.responseText);

                    var coords = Ext.ux.osrm.RoutingGeometry.show(responseObj);

                    // Create an array with LatLng coordinates
                    var latlngs = []
                    Ext.each(coords, function(item, index, allItems){
                        latlngs.push(new L.LatLng(item[0],item[1]));
                    });

                    // Create the new layer and add it to the map
                    route = new L.Polyline(latlngs, {
                        color: 'blue'
                    });
                    route.on('mouseover', function(e){
                        statusControl.setText(Ext.ux.ts.tr('Click to add new via point'));
                    });
                    route.on('mouseout', function(e){
                        statusControl.setText('');
                    });
                    route.on('click', function(e){
                        addViaMarker(e.latlng);
                    });
                    map.addLayer(route);

                    // Recenter the map if requested
                    if(recenter){
                        map.fitBounds(new L.LatLngBounds(latlngs));
                    }

                    // Update the route instructions panel
                    var instructionsDiv = dh.overwrite('route-result-panel', {
                        tag: 'table',
                        cls: 'result-table'
                    });
                    var instructions = responseObj.route_instructions
                    var total_distance = 0;

                    // Loop all routing steps
                    for (var i = 0; i < instructions.length; i++){
                        // To differentiate better the steps highlight even and odd
                        // rows. Even rows get a darker background.
                        var cls = (i%2==0) ? 'route-instructions-even' : '';

                        // Get the street instructions, if the street name is missing
                        // or empty, drop also the "on".
                        var street = (instructions[i][1] != '') ? "on " + instructions[i][1] : "";

                        // Get the distance of the current step and format it readable
                        var step_distance = instructions[i][2];
                        // Parse the step distance as integer to prevent strint concatenation
                        total_distance += parseInt(step_distance);
                        var dist = step_distance > 1000 ? Number(parseInt(step_distance/10)/100) + " km" : step_distance + " m";

                        // Append the current row to the template
                        routeInstructionsTpl.append(instructionsDiv, [(i+1), cls, instructions[i][6], street, dist]);
                    }

                    var credits = dh.append(instructionsDiv, {
                        tag: 'tr',
                        id: 'credit-row',
                        children: [{
                            'class': 'route-credit',
                            colspan: 3,
                            children: [{
                                html: Ext.ux.ts.tr('Routing powered by '),
                                tag: 'span'
                            },{
                                'class': 'external',
                                href: 'http://project-osrm.org/',
                                html: 'OSRM',
                                tag: 'a'
                            }],
                            tag: 'td'
                        }]
                    } );

                    // Calculate hours and minutes from the total time in seconds
                    var total_time = responseObj.route_summary.total_time;
                    var total_hours = parseInt(total_time / 3600);
                    var total_mins = parseInt(((total_time / 3600) - total_hours) * 60);

                    // Total distance in meters
                    //var total_distance = responseObj.features[0].properties.total_distance;
                    var total_km = Number(parseInt(total_distance/10)/100);

                    // Create the summary division
                    var summaryDiv = dh.overwrite('route-summary-panel', {
                        tag: 'div'
                    });
                    routeSummaryTpl.append(summaryDiv, [total_km, total_hours, total_mins]);
                },
                url: '/directions'

            });
        }
        // If end and/or start marker are null, remove the route from the map
        else {
            clearViaMarkers();
            // Delete the routing summary by just overwriting it
            dh.overwrite('route-summary-panel', '');
            // Delete also the routing instructions by just overwriting it
            dh.overwrite('route-result-panel', '');
            if(route) {
                map.removeLayer(route);
            }
        }
    }
    
    // Get all links that point to an external website
    var externalLinks = Ext.select('.external');
    // Change the href element on click events
    externalLinks.on('click', function(event, htmlElement, o){
        var originalHref = htmlElement.href;
        htmlElement.href = "/redirect/?u=" + escape(originalHref);
    });

});


Ext.namespace("Ext.ux");

Ext.ux.LanguageButton = Ext.extend(Ext.Button, {

    language: 'Lao',

    languageCode: 'lo',

    url: '/',

    initComponent: function(){

        var config = {
            enableToggle: true,
            handler: function(evt) {
                Ext.util.Cookies.set('_osm_la[__LANG__]', this.languageCode, new Date().add(Date.DAY, 30));
                window.location.href = this.url + "?lang=" + this.languageCode;
            },
            listeners: {
                mouseover: function(button, evt){
                    this.statusControl.setText(Ext.ux.ts.tr(this.language));
                },
                mouseout: function(button, evt){
                    this.statusControl.setText('');
                }
            },
            iconCls: this.languageCode + "-button",
            pressed: this.currentLang == this.languageCode,
            toggleGroup: "languageToggleGroup"
        };

        Ext.apply(this, config);

        //call the superclass constructor
        Ext.ux.LanguageButton.superclass.initComponent.call(this);

    }
});

Ext.reg('ux_languagebutton', Ext.ux.LanguageButton);

Ext.ux.LanguageToolbar = Ext.extend(Ext.Toolbar, {

    initComponent: function(){

        var config = {
            items: ['->',/*{
                id: 'language-label',
                text: Ext.ux.ts.tr('Language'),
                xtype: 'label'
            },*/{
                currentLang: this.currentLang,
                language: 'Lao',
                languageCode: 'lo',
                statusControl: this.statusControl,
                style: {
                    padding: '0px 2px 0px'
                },
                url: this.url,
                xtype: 'ux_languagebutton'
            },{
                currentLang: this.currentLang,
                language: 'English',
                languageCode: 'en',
                statusControl: this.statusControl,
                style: {
                    padding: '0px 2px 0px'
                },
                url: this.url,
                xtype: 'ux_languagebutton'
            }]
        }

        Ext.apply(this, config);

        //call the superclass constructor
        Ext.ux.LanguageToolbar.superclass.initComponent.call(this);
    }
});

Ext.reg('ux_languagetoolbar', Ext.ux.LanguageToolbar);

Ext.ux.LocationComboBox = Ext.extend(Ext.form.ComboBox, {

    map: null,
    
    initComponent: function(){

        var config = {
            emptyText: Ext.ux.ts.tr('Search village, hotel, restaurant, shop, etc.'),
            store: new Ext.data.JsonStore({
                proxy : new Ext.data.HttpProxy({
                    method: 'GET',
                    url: '/places'
                }),
                root: 'data',
                idProperty: 'hash',
                fields: [ 'name', 'hash', 'feature' ]
            }),
            loadingText: Ext.ux.ts.tr('Loading...'),
            valueField: 'hash',
            displayField: 'name',
            iconClsField: 'feature',
            typeAhead: false,
            minChars: 3,
            mode: 'remote',
            queryParam: 'q',  //contents of the field sent to server.
            hideTrigger: true,    //hide trigger so it doesn't look like a combobox.
            selectOnFocus:true,
            tpl:  '<tpl for="."><div class="x-combo-list-item">'
            + '<table><tbody><tr>'
            + '<td>'
            + '<div class="x-poi-{feature} x-icon-combo-icon"></div></td>'
            + '<td>{name}</td>'
            + '</tr></tbody></table>'
            + '</div></tpl>',
            xtype: 'combo'
        };

        Ext.apply(this, config);

        //call the superclass constructor
        Ext.ux.LocationComboBox.superclass.initComponent.call(this);
    },

    setLocationValue: function(latLng) {
        var v = "Lat: " + Math.round(latLng.lat*10000)/10000 + ", Lon: " + Math.round(latLng.lng*10000)/10000;
        this.setValue(v);
    }
});

Ext.reg('ux_locationcombo', Ext.ux.LocationComboBox);

/**
 *
 * OpenStreetMap.la
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.*
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * @class Ext.ux.MapPanel
 * @extends Ext.Panel
 * @author Adrian Weber
 */
Ext.ux.MapPanel = Ext.extend(Ext.Panel, {

    center: null,

    editTpl: null,

    editEl: null,

    map: null,

    zoom: null,

    initComponent: function(){

        var defConfig = {};

        Ext.applyIf(this,defConfig);

        Ext.ux.MapPanel.superclass.initComponent.call(this);

    },
    afterRender: function(){

        var wh = this.ownerCt.getSize();
        Ext.applyIf(this, wh);

        Ext.ux.MapPanel.superclass.afterRender.call(this);

        this.map.setView(this.center,this.zoom);

        // Update the map state after the map has been dragged
        this.map.on('dragend', function(evt){
            // Set the new center of the map
            this.center = this.map.getCenter();
            this.updateTpl();
            this.updateCookie();
        }, this);

        // Update the map zoom after the map has been zoomed
        this.map.on('zoomend', function(evt){
            this.zoom = this.map.getZoom();
            this.updateTpl();
            this.updateCookie();
        }, this);

        this.updateTpl();
    },
    onResize: function(w, h){
        Ext.ux.MapPanel.superclass.onResize.call(this, w, h);

        if (this.map) {
            this.map.setView(this.center,this.zoom);
            this.map.invalidateSize();
        }
    },
    setSize: function(width, height, animate){
        Ext.ux.MapPanel.superclass.setSize.call(this, width, height, animate);
        if (this.map) {
            this.map.setView(this.center,this.zoom);
            this.map.invalidateSize();
        }
    },
    getMap: function(){
        return this.map;
    },
    updateTpl: function(){
        // Update the template that links to Potlatch on the main
        // openstreetmap.org page with the current center and zoom
        if(this.editTpl){
            this.editTpl.overwrite(
                this.editEl,
                {
                    zoom: this.map.getZoom(),
                    lat: this.center.lat,
                    lon: this.center.lng
                });
        }
    },
    /**
     * Sets the current map center and zoom level to a Cookie.
     */
    updateCookie: function(){
        var value = this.map.getCenter().lng + " "
        + this.map.getCenter().lat + " "
        + this.map.getZoom();
        Ext.util.Cookies.set('_osm_la[__LOCATION__]', value, new Date().add(Date.DAY, 30));
    }
});

Ext.reg('mappanel', Ext.ux.MapPanel);



/* (C) 2009 Ivan Boldyrev <lispnik@gmail.com>
 *
 * Fgh is a fast GeoHash implementation in JavaScript.
 *
 * Fgh is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * Fgh is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this software; if not, see <http://www.gnu.org/licenses/>.
 */
(function () {
    var _tr = "0123456789bcdefghjkmnpqrstuvwxyz";
    /* This is a table of i => "even bits of i combined".  For example:
     * #b10101 => #b111
     * #b01111 => #b011
     * #bABCDE => #bACE
     */
    var _dm = [0, 1, 0, 1, 2, 3, 2, 3, 0, 1, 0, 1, 2, 3, 2, 3,
    4, 5, 4, 5, 6, 7, 6, 7, 4, 5, 4, 5, 6, 7, 6, 7];

    /* This is an opposit of _tr table: it maps #bABCDE to
     * #bA0B0C0D0E.
     */
    var _dr = [0, 1, 4, 5, 16, 17, 20, 21, 64, 65, 68, 69, 80,
    81, 84, 85, 256, 257, 260, 261, 272, 273, 276, 277,
    320, 321, 324, 325, 336, 337, 340, 341];

    function _cmb (str, pos) {
        return (_tr.indexOf(str.charAt(pos)) << 5) | (_tr.indexOf(str.charAt(pos+1)));
    }

    function _unp(v) {
        return _dm[v & 0x1F] | (_dm[(v >> 6) & 0xF] << 3);
    }

    function _sparse (val) {
        var acc = 0, off = 0;

        while (val > 0) {
            low = val & 0xFF;
            acc |= _dr[low] << off;
            val >>= 8;
            off += 16;
        }
        return acc;
    }

    window['Fgh'] = {
        decode: function (str) {
            var L = str.length, i, w, ln = 0.0, lt = 0.0;

            // Get word; handle odd size of string.
            if (L & 1) {
                w = (_tr.indexOf(str.charAt(L-1)) << 5);
            } else {
                w = _cmb(str, L-2);
            }
            lt = (_unp(w)) / 32.0;
            ln = (_unp(w >> 1)) / 32.0;
            
            for (i=(L-2) & ~0x1; i>=0; i-=2) {
                w = _cmb(str, i);
                lt = (_unp(w) + lt) / 32.0;
                ln = (_unp(w>>1) + ln) / 32.0;
            }
            return {
                lat:  180.0*(lt-0.5),
                lon: 360.0*(ln-0.5)
            };
        },
        
        encode: function (lat, lon, bits) {
            lat = lat/180.0+0.5;
            lon = lon/360.0+0.5;
            
            /* We generate two symbols per iteration; each symbol is 5
             * bits; so we divide by 2*5 == 10.
             */
            var r = '', l = Math.ceil(bits/10), hlt, hln, b2, hi, lo, i;

            for (i = 0; i < l; ++i) {
                lat *= 0x20;
                lon *= 0x20;

                hlt = Math.min(0x1F, Math.floor(lat));
                hln = Math.min(0x1F, Math.floor(lon));
                
                lat -= hlt;
                lon -= hln;
                
                b2 = _sparse(hlt) | (_sparse(hln) << 1);
                
                hi = b2 >> 5;
                lo = b2 & 0x1F;

                r += _tr.charAt(hi) + _tr.charAt(lo);
            }
            
            r = r.substr(0, Math.ceil(bits/5));
            return r;
        },
    
        checkValid: function(str) {
            return !!str.match(/^[0-9b-hjkmnp-z]+$/);
        }
    }
})();


// OSRM routing geometry, decodes the Polyline from OSRM server
// Adapted from
// https://github.com/DennisSchiefer/Project-OSRM-Web/blob/develop/WebContent/routing/OSRM.RoutingGeometry.js

Ext.namespace("Ext.ux.osrm");

Ext.ux.osrm.PRECISION = 6;
Ext.ux.osrm.RoutingGeometry = {

    // show route geometry - if there is a route
    show: function(response) {
        var geometry = Ext.ux.osrm.RoutingGeometry._decode(response.route_geometry, Ext.ux.osrm.PRECISION );
        return geometry;
    },

    //decode compressed route geometry
    _decode: function(encoded, precision) {
        precision = Math.pow(10, -precision);
        var len = encoded.length, index=0, lat=0, lng = 0, array = [];
        while (index < len) {
            var b, shift = 0, result = 0;
            do {
                b = encoded.charCodeAt(index++) - 63;
                result |= (b & 0x1f) << shift;
                shift += 5;
            } while (b >= 0x20);
            var dlat = ((result & 1) ? ~(result >> 1) : (result >> 1));
            lat += dlat;
            shift = 0;
            result = 0;
            do {
                b = encoded.charCodeAt(index++) - 63;
                result |= (b & 0x1f) << shift;
                shift += 5;
            } while (b >= 0x20);
            var dlng = ((result & 1) ? ~(result >> 1) : (result >> 1));
            lng += dlng;
            //array.push( {lat: lat * precision, lng: lng * precision} );
            array.push( [lat * precision, lng * precision] );
        }
        return array;
    }
};
