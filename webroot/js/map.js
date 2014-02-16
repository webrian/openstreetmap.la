// Extend L.Marker to set some default mouseover and mouseout events
L.OsmMarker = L.Marker.extend({
    options: {
        //mouseoverText: Ext.ux.ts.tr('Drag to change route or double click to remove'),
        //mouseoutText: '',
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

function addPlaceMarker(latLng, panTo){
    // First remove an existing place marker (if any)
    if(placemarker) {
        map.removeLayer(placemarker);
    }

    placemarker = new L.OsmMarker(latLng,{
        //ctrl: statusControl,
        //mouseoverText: Ext.ux.ts.tr('Double click to remove')
    });
    placemarker.on('dblclick', function(evt){
        //clearPlaceMarker();
    });
    placemarker.on('dragend', function(evt){
        // Update the start combo field after dragend
        //searchComboBox.setLocationValue(placemarker.getLatLng());
    });
    map.addLayer(placemarker);

    if(panTo) {
        map.panTo(latLng);
    }
}

var map, placemarker;

$(document).ready(function() {
    $("[rel=tooltip]").tooltip();
    if (document.body.clientWidth <= 767) {
        $("#map").css("class", "col-sm-12 col-lg-12");
        $("#sidebar").css("display", "none");
    };
});
$(window).resize(function() {
    $(".tt-dropdown-menu").css("max-height", $("#container").height()-$(".navbar").height()-20);
    if (document.body.clientWidth <= 767) {
        $("#map").css("class", "col-sm-12 col-lg-12");
        $("#sidebar").css("display", "none");
    } else {
        $("#map").css("class", "col-sm-9 col-lg-9");
        $("#sidebar").css("display", "block");
    };
});

// The attribution string with hyperlinks
var attribution = 'map &copy; <a href=\"http://www.osm-tools.org\">osm-tools.org</a>,\n\
            data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors';

var osmLayer = L.tileLayer(osmtoolsUrl, {
    inZoom: 5,
    maxZoom: 18,
    attribution: attribution
});

var satelliteUrl = '/tms/1.0.0/landsat8/{z}/{x}/{y}.jpeg';
var satelliteLayer = new L.TileLayer(satelliteUrl, {
    attribution: 'Landsat 8 <a href="http://eros.usgs.gov/#/About_Us/Customer_Service/Data_Citation">imagery available from the U.S. Geological Survey</a>,\n\
            data &copy; <a href=\"http://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors',
    minZoom: 5,
    maxZoom: 14,
    tms: true
});


map = L.map("map", {
    zoom: initZoom,
    center: initCenter,
    layers: [osmLayer]
});

$("#toggle").click(function() {
    $("#toggle i").toggleClass("fa fa-check-square-o fa fa-map-marker");
    $("#map").toggleClass("col-sm-9 col-lg-9 col-sm-12 col-lg-12");
    $("#sidebar").toggle();
    if (document.body.clientWidth <= 767) {
        $("#map").toggle();
    };
    map.invalidateSize();
    return false;
});

$("#streets-button").click(function(){
    $("#streets-button").addClass("active");
    $("#hybrid-button").removeClass("active");
    map.removeLayer(satelliteLayer);
    map.addLayer(osmLayer);
});

$("#hybrid-button").click(function(){
    $("#hybrid-button").addClass("active");
    $("#streets-button").removeClass("active");
    map.removeLayer(osmLayer);
    map.addLayer(satelliteLayer);
});

var engine = new Bloodhound({
    remote: 'http://openstreetmap/places?q=%QUERY',
    datumTokenizer: function(d) {
        return Bloodhound.tokenizers.whitespace(d.val);
    },
    queryTokenizer: function(q){
        return "ok";
    }
});

engine.initialize();

$("#searchbox").typeahead({
    minLength: 3
},{
    displayKey: "name",
    name: "placename",
    source: function (query, process) {
        return $.get('/places', {
            q: query
        }, function (data) {
            var res = [];
            $(data.data).each(function(index, place){
                res.push(place.name);
            });
            return process(data.data);
        });
    },
    templates: {
        suggestion: function(obj){
            return '<p><i class="mapnik-icon-' + obj.feature + '"></i>&nbsp;&nbsp;' + obj.name + '</p>';
        }
    }
});

$("#searchbox").on("typeahead:selected", function(event, record){
    // Get the coordinates from the selected record
    var c = Fgh.decode(record.hash);
    var coords = new L.LatLng(c.lat, c.lon);
    addPlaceMarker(coords, true);
});

/*


$("input[name='basemapLayers']").change(function () {
    // Remove unchecked layers
    $("input:radio[name='basemapLayers']:not(:checked)").each(function () {
        map.removeLayer(window[$(this).attr("id")]);
    });
    // Add checked layer
    $("input:radio[name='basemapLayers']:checked").each(function () {
        map.addLayer(window[$(this).attr("id")]);
    });
});

$("input:checkbox[name='overlayLayers']").change(function () {
    var layers = [];
    function sortByKey(array, key) {
        return array.sort(function (a, b) {
            var x = a[key];
            var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        });
    }
    if ($("#" + $(this).attr("id")).is(":checked")) {
        $("input:checkbox[name='overlayLayers']").each(function () {
            // Remove all overlay layers
            map.removeLayer(window[$(this).attr("id")]);
            if ($("#" + $(this).attr("id")).is(":checked")) {
                // Add checked layers to array for sorting
                layers.push({
                    "z-index": $(this).attr("z-index"),
                    "layer": $(this)
                });
            }
        });
        // Sort layers array by z-index
        var orderedLayers = sortByKey(layers, "z-index");
        // Loop through ordered layers array and add to map in correct order
        $.each(orderedLayers, function () {
            map.addLayer(window[$(this)[0].layer[0].id]);
        });
    } else {
        // Simply remove unchecked layers
        map.removeLayer(window[$(this).attr("id")]);
    }
});

// Basemap Layers
var mapquestOSM = L.tileLayer("http://{s}.laostile.osm-tools.org/osm_en/{z}/{x}/{y}.png", {
    maxZoom: 19,
    subdomains: ["otile1", "otile2", "otile3", "otile4"],
    attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA.'
});
var mapquestOAM = L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg", {
    maxZoom: 18,
    subdomains: ["oatile1", "oatile2", "oatile3", "oatile4"],
    attribution: 'Tiles courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a>. Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency'
});
var mapquestHYB = L.layerGroup([L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/sat/{z}/{x}/{y}.jpg", {
    maxZoom: 18,
    subdomains: ["oatile1", "oatile2", "oatile3", "oatile4"]
}), L.tileLayer("http://{s}.mqcdn.com/tiles/1.0.0/hyb/{z}/{x}/{y}.png", {
    maxZoom: 19,
    subdomains: ["oatile1", "oatile2", "oatile3", "oatile4"],
    attribution: 'Labels courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png">. Map data (c) <a href="http://www.openstreetmap.org/" target="_blank">OpenStreetMap</a> contributors, CC-BY-SA. Portions Courtesy NASA/JPL-Caltech and U.S. Depart. of Agriculture, Farm Service Agency'
})]);

var museums = L.geoJson(null, {
    pointToLayer: function (feature, latlng) {
        return L.marker(latlng, {
            icon: L.icon({
                iconUrl: "img/museum.png",
                iconSize: [24, 28],
                iconAnchor: [12, 28],
                popupAnchor: [0, -25]
            }),
            title: feature.properties.NAME,
            riseOnHover: true
        });
    },
    onEachFeature: function (feature, layer) {
        if (feature.properties) {
            var content =   "<table class='table table-striped table-bordered table-condensed'>"+
            "<tr><th>Name</th><td>" + feature.properties.NAME + "</td></tr>"+
            "<tr><th>Phone</th><td>" + feature.properties.TEL + "</td></tr>"+
            "<tr><th>Address</th><td>" + feature.properties.ADRESS1 + "</td></tr>"+
            "<tr><th>Website</th><td><a class='url-break' href='" + feature.properties.URL + "' target='_blank'>" + feature.properties.URL + "</a></td></tr>"+
            "<table>";
            if (document.body.clientWidth <= 767) {
                layer.on({
                    click: function(e) {
                        $("#feature-title").html(feature.properties.NAME);
                        $("#feature-info").html(content);
                        $("#featureModal").modal("show");
                    }
                });

            } else {
                layer.bindPopup(content, {
                    maxWidth: "auto",
                    closeButton: false
                });
            };
            museumSearch.push({
                value: layer.feature.properties.NAME,
                tokens: [layer.feature.properties.NAME],
                layer: "Museums",
                id: L.stamp(layer),
                lat: layer.feature.geometry.coordinates[1],
                lng: layer.feature.geometry.coordinates[0]
            });
        }
    }
});

map = L.map("map", {
    zoom: 10,
    center: [18, 102],
    layers: [mapquestOSM]
});
// Hack to preserver layer order in Layer control
map.removeLayer(subwayLines);

// Larger screens get expanded layer control
if (document.body.clientWidth <= 767) {
    var isCollapsed = true;
} else {
    var isCollapsed = false;
};

// Highlight search box text on click
$("#searchbox").click(function () {
    $(this).select();
});

// Typeahead search functionality
$(document).one("ajaxStop", function() {
    $("#loading").hide();
    $("#searchbox").typeahead([{
        name: "Boroughs",
        local: boroughSearch,
        minLength: 2,
        header: "<h4 class='typeahead-header'>Boroughs</h4>"
    },{
        name: "Theaters",
        local: theaterSearch,
        minLength: 2,
        limit: 10,
        header: "<h4 class='typeahead-header'><img src='img/theater.png' width='24' height='28'>&nbsp;Theaters</h4>"
    },{
        name: "Museums",
        local: museumSearch,
        minLength: 2,
        limit: 10,
        header: "<h4 class='typeahead-header'><img src='img/museum.png' width='24' height='28'>&nbsp;Museums</h4>"
    },{
        name: "GeoNames",
        remote: {
            url: "http://ws.geonames.org/searchJSON?featureClass=P&maxRows=5&countryCode=US&name_startsWith=%QUERY",
            beforeSend: function(jqXhr, settings) {
                settings.url += "&east="+map.getBounds().getEast()+"&west="+map.getBounds().getWest()+"&north="+map.getBounds().getNorth()+"&south="+map.getBounds().getSouth();
            },
            filter: function(parsedResponse) {
                var dataset = [];
                for(i = 0; i < parsedResponse.geonames.length; i++) {
                    dataset.push({
                        value: parsedResponse.geonames[i].name,
                        tokens: [parsedResponse.geonames[i].name],
                        layer: "GeoNames",
                        lat: parsedResponse.geonames[i].lat,
                        lng: parsedResponse.geonames[i].lng
                    });
                }
                return dataset;
            }
        },
        minLength: 2,
        limit: 5,
        header: "<h4 class='typeahead-header'><img src='img/globe.png' width='25' height='25'>&nbsp;GeoNames Places</h4>"
    }]).on("typeahead:selected", function (obj, datum) {
        if (datum.layer === "Boroughs") {
            map.fitBounds(datum.bounds);
        };
        if (datum.layer === "Theaters") {
            if (!map.hasLayer(theaters)) {
                map.addLayer(theaters);
                $("#theaters").prop("checked", true);
            };
            map.setView([datum.lat, datum.lng], 17);
            if (map._layers[datum.id]) {
                map._layers[datum.id].openPopup();
            };
        };
        if (datum.layer === "Museums") {
            if (!map.hasLayer(museums)) {
                map.addLayer(museums);
                $("#museums").prop("checked", true);
            };
            map.setView([datum.lat, datum.lng], 17);
            if (map._layers[datum.id]) {
                map._layers[datum.id].openPopup();
            };
        };
        if (datum.layer === "GeoNames") {
            map.setView([datum.lat, datum.lng], 14);
        };
        if ($("#navbar-collapse").height() > 50) {
            $("#navbar-collapse").collapse("hide");
        };
    }).on("typeahead:initialized ", function () {
        $(".tt-dropdown-menu").css("max-height", 300);
    }).on("typeahead:opened", function () {
        $(".navbar-collapse.in").css("max-height", $(document).height()-$(".navbar-header").height());
        $(".navbar-collapse.in").css("height", $(document).height()-$(".navbar-header").height());
    }).on("typeahead:closed", function () {
        $(".navbar-collapse.in").css("max-height", "");
        $(".navbar-collapse.in").css("height", "");
    });
});

// Placeholder hack for IE
if (navigator.appName == "Microsoft Internet Explorer") {
    $("input").each( function () {
        if ($(this).val() == "" && $(this).attr("placeholder") != "") {
            $(this).val($(this).attr("placeholder"));
            $(this).focus(function () {
                if ($(this).val() == $(this).attr("placeholder")) $(this).val("");
            });
            $(this).blur(function () {
                if ($(this).val() == "") $(this).val($(this).attr("placeholder"));
            });
        }
    });
}

*/

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

RoutingGeometry = {

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