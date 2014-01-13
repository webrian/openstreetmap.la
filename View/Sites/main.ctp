<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>BootLeaf Template</title>

        <!-- Core CSS -->
        <link href="/lib/bootstrap-3.0.3/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/lib/font-awesome-4.0.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="/lib/typeahead.js/typeahead.js-bootstrap.css" rel="stylesheet" type="text/css"> <!--https://github.com/jharding/typeahead.js-bootstrap.css-->
        <link href="/lib/leaflet-0.5.1/dist/leaflet.css" rel="stylesheet" type="text/css">

        <!-- Custom styles for this template -->
        <style>
            html, body, #sidebar, #container {
                height: 100%;
                margin: 0px;
            }
            body {
                padding-top: 50px;
            }
            label {
                font-weight: normal;
            }
            #map {
                height: 100%;
                margin: 0px;
                -webkit-box-shadow: 0 -1px 10px rgba(0,0,0,0.5);
                -moz-box-shadow: 0 -1px 10px rgba(0,0,0,0.5);
                box-shadow: 0 -1px 10px rgba(0,0,0,0.5);
            }
            #loading {
                position: absolute;
                width: 220px;
                height: 19px;
                top: 50%;
                left: 50%;
                margin: -10px 0 0 -110px;
                z-index: 20001;
            }
            #searchbox {
                -webkit-border-top-left-radius: 4px;
                -webkit-border-bottom-left-radius: 4px;
                -moz-border-top-left-radius: 4px;
                -moz-border-bottom-left-radius: 4px;
                border-top-left-radius: 4px;
                border-bottom-left-radius: 4px;
            }
            .table {
                margin-bottom: 0px;
            }
            .navbar .navbar-brand {
                font-weight: bold;
                font-size: 22px;
                color: white;
                white-space: nowrap;
            }
            .navbar-collapse.in {
                overflow-y: hidden;
            }
            .tt-dropdown-menu {
                overflow: auto;
            }
            .tt-hint, .tt-query {
                display: block;
                width: 100%;
                height: 34px;
                padding: 6px 12px;
                font-size: 14px;
                border-radius: 4px;
            }
            .typeahead-header {
                margin: 0 5px 5px 5px;
                padding: 3px 0;
                border-bottom: 2px solid #333;
            }
            .tt-suggestion + .tt-suggestion {
                border-top: 1px solid #ccc;
            }
            .search-container {
                width: 250px;
            }
            .leaflet-popup-content {
                margin-top: 5px;
                margin-bottom: 5px;
                margin-left: 5px;
                margin-right: 5px;
            }
            .leaflet-popup-content-wrapper {
                border-radius: 5px;
            }
            .panel-heading a:hover {
                text-decoration: none;
            }
            @media (max-width: 992px) {
                .navbar .navbar-brand {
                    font-size: 18px;
                    float: left;
                }
                .search-container {
                    width: 150px;
                }
            }
            @media (max-width: 767px){
                .search-container {
                    width: 100%;
                }
                .url-break {
                    word-break: break-all;
                    word-break: break-word;
                    -webkit-hyphens: auto;
                    hyphens: auto;
                }
            }
            /* Print Handling */
            @media print {
                .navbar, .toggle, #sidebar {
                    display: none !important;
                }
            }
        </style>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle btn" data-toggle="collapse" data-target=".navbar-collapse" style="height: 34px; padding: 5px 10px; margin-right: 10px;"><i class="fa fa-ellipsis-v" style="color: white"></i></button>
                <button id="toggle" type="button" class="navbar-toggle btn" style="height: 34px; padding: 5px 10px; margin-right: 10px;"><i id="toggleIcon" class="fa fa-check-square-o" style="color: white"></i></button>
                <a class="navbar-brand" href="#">BootLeaf Sidebar</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar-collapse">
                <form class="navbar-form navbar-right">
                    <div class="input-group search-container">
                        <input id="searchbox" type="text" class="form-control" placeholder="Search">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                </form>
                <ul class="nav navbar-nav">
                    <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="$('#aboutModal').modal('show'); return false;"><i class="fa fa-question-circle" style="color: white"></i>&nbsp;&nbsp;About</a></li>
                    <li class="dropdown">
                        <a id="toolsDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe" style="color: white"></i>&nbsp;&nbsp;Tools <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="map.fitBounds(boroughs.getBounds()); return false;"><i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;Zoom To Full Extent</a></li>
                            <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="$('#legendModal').modal('show'); return false;"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Show Legend</a></li>
                            <li class="divider"></li>
                                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" onclick="$('#loginModal').modal('show'); return false;"><i class="fa fa-user"></i>&nbsp;&nbsp;Login</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a id="downloadDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cloud-download" style="color: white"></i>&nbsp;&nbsp;Download <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a href="data/boroughs.geojson" download="boroughs.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Boroughs</a></li>
                                <li><a href="data/subways.geojson" download="subways.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Subway Lines</a></li>
                                <li><a href="data/DOITT_THEATER_01_13SEPT2010.geojson" download="theaters.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Theaters</a></li>
                                <li><a href="data/DOITT_MUSEUM_01_13SEPT2010.geojson" download="museums.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Museums</a></li>
                            </ul>
                    </li>
                </ul>
            </div><!--/.navbar-collapse -->
        </div>

        <div class="row" id="container">
            <div class="col-sm-3 col-lg-3" id="sidebar" style="padding: 10px; overflow: auto;">

                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_" href="#overlay-layers">
                                        Overlay Layers
                                    </a>
                                </div>
                                <div id="overlay-layers" class="panel-collapse collapse in">
                                    <div class="panel-body" style="padding: 0px 15px;">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="overlayLayers" id="boroughs" z-index="2" checked>
                                                Boroughs
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="overlayLayers" id="subwayLines" z-index="1">
                                                Subway Lines
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="overlayLayers" id="theaters" z-index="3" checked>
                                                Theaters&nbsp;<img src="img/theater.png" width="24" height="28">
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="overlayLayers" id="museums" z-index="3">
                                                Museums&nbsp;<img src="img/museum.png" width="24" height="28">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_" href="#basemap-layers">
                                        Basemaps
                                    </a>
                                </div>
                                <div id="basemap-layers" class="panel-collapse collapse in">
                                    <div class="panel-body" style="padding: 0px 15px;">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="basemapLayers" id="mapquestOSM" checked>
                                                Streets
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="basemapLayers" id="mapquestOAM">
                                                Imagery
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="basemapLayers" id="mapquestHYB">
                                                Hybrid
                                            </label>
                                        </div>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 col-lg-9" id="map">
                <div id="loading" style="display: block;">
                    <div class="loading-indicator">
                        <div class="progress progress-striped active">
                            <div class="progress-bar progress-bar-info" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="aboutModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Welcome to the BootLeaf Sidebar template!</h4>
                    </div>

                    <div class="modal-body">
                         <ul id="aboutTabs" class="nav nav-tabs">
                            <li class="active"><a href="#about" data-toggle="tab"><i class="fa fa-question-circle"></i>&nbsp;About the project</a></li>
                            <li><a href="#contact" data-toggle="tab"><i class="fa fa-envelope"></i>&nbsp;Contact us</a></li>
                            <li><a href="#disclaimer" data-toggle="tab"><i class="fa fa-exclamation-circle"></i>&nbsp;Disclaimer</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i>&nbsp;Metadata <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#boroughs-tab" data-toggle="tab">Boroughs</a></li>
                                    <li><a href="#subway-lines-tab" data-toggle="tab">Subway Lines</a></li>
                                    <li><a href="#theaters-tab" data-toggle="tab">Theaters</a></li>
                                    <li><a href="#museums-tab" data-toggle="tab">Museums</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div id="aboutTabsContent" class="tab-content" style="padding-top: 10px;">
                            <div class="tab-pane fade active in" id="about">
                                <p>A simple template for building web mapping applications with <a href="http://getbootstrap.com/">Bootstrap 3</a> and <a href="http://leafletjs.com/" target="_blank">Leaflet</a>.</p>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        Features
                                    </div>
                                    <ul class="list-group">
                                        <li class="list-group-item">Fullscreen mobile-friendly map template with responsive navbar, sidebar, and modal placeholders</li>
                                        <li class="list-group-item">jQuery loading of external GeoJSON files</li>
                                        <li class="list-group-item">Elegant client-side multi-layer feature search with autocomplete using <a href="http://twitter.github.io/typeahead.js/" target="_blank">typeahead.js</a></li>
                                        <li class="list-group-item">Integration of Bootstrap tables into Leaflet popups</li>
                                        <li class="list-group-item">Logic for minimizing Sidebar on small screens</li>
                                        <li class="list-group-item">Custom layer control with functions for defining and retaining vector z-index when toggling layer visibility.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="tab-pane fade text-danger" id="disclaimer">
                                <p>The data provided on this site is for informational and planning purposes only.</p>
                                <p>Absolutely no accuracy or completeness guarantee is implied or intended. All information on this map is subject to such variations and corrections as might result from a complete title search and/or accurate field survey.</p>
                            </div>
                            <div class="tab-pane fade" id="contact">
                                <form id="contact-form">
                                    <fieldset>
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input type="text" class="form-control" id="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input type="text" class="form-control" id="email">
                                        </div>
                                        <div class="form-group">
                                            <label for="comment">Comment:</label>
                                            <textarea class="form-control" rows="3" id="comment"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary pull-right" data-dismiss="modal">Submit</button>
                                    </fieldset>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="boroughs-tab">
                                <p>Borough data courtesy of <a href="http://www.nyc.gov/html/dcp/html/bytes/meta_dis_nyboroughwi.shtml" target="_blank">New York City Department of City Planning</a></p>
                            </div>
                            <div class="tab-pane fade" id="subway-lines-tab">
                                <p><a href="http://spatialityblog.com/2010/07/08/mta-gis-data-update/#datalinks" target="_blank">MTA Subway data</a> courtesy of the <a href="http://www.urbanresearch.org/about/cur-components/cuny-mapping-service" target="_blank">CUNY Mapping Service at the Center for Urban Research</a></p>
                            </div>
                            <div class="tab-pane fade" id="theaters-tab">
                                <p>Theater data courtesy of <a href="https://data.cityofnewyork.us/Recreation/Theaters/kdu2-865w" target="_blank">NYC Department of Information & Telecommunications (DoITT)</a></p>
                            </div>
                            <div class="tab-pane fade" id="museums-tab">
                                <p>Museum data courtesy of <a href="https://data.cityofnewyork.us/Recreation/Museums-and-Galleries/sat5-adpb" target="_blank">NYC Department of Information & Telecommunications (DoITT)</a></p>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="legendModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Map Legend</h4>
                    </div>
                    <div class="modal-body">
                        <p>Map Legend goes here...</p>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="loginModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Login</h4>
                    </div>
                    <div class="modal-body">
                        <form id="contact-form">
                            <fieldset>
                                <div class="form-group">
                                    <label for="name">Username:</label>
                                    <input type="text" class="form-control" id="username">
                                </div>
                                <div class="form-group">
                                    <label for="email">Password:</label>
                                    <input type="password" class="form-control" id="password">
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary pull-right" data-dismiss="modal">Login</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" id="featureModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title text-primary" id="feature-title"></h4>
                    </div>
                    <div class="modal-body" id="feature-info">
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/typeahead.js/typeahead.min.js"></script> <!--https://github.com/twitter/typeahead.js/-->
        <script type="text/javascript" src="http://cdn.leafletjs.com/leaflet-0.7.1/leaflet.js?2"></script>
        <script type="text/javascript">
            var map, boroughSearch = [], theaterSearch = [], museumSearch = [];

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

            // Overlay Layers
            var boroughs = L.geoJson(null, {
                style: function (feature) {
                    return {
                        color: "black",
                        fill: false,
                        opacity: 1,
                        clickable: false
                    };
                },
                onEachFeature: function (feature, layer) {
                    boroughSearch.push({
                        value: layer.feature.properties.BoroName,
                        tokens: [layer.feature.properties.BoroName],
                        layer: "Boroughs",
                        id: L.stamp(layer),
                        bounds: layer.getBounds()
                    });
                }
            });
            $.getJSON("data/boroughs.geojson", function (data) {
                boroughs.addData(data);
            }).complete(function () {
                map.fitBounds(boroughs.getBounds());
            });

            var subwayLines = L.geoJson(null, {
                style: function (feature) {
                    if (feature.properties.route_id === "1" || feature.properties.route_id === "2" || feature.properties.route_id === "3") {
                        return {
                            color: "#ff3135",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "4" || feature.properties.route_id === "5" || feature.properties.route_id === "6") {
                        return {
                            color: "#009b2e",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "7") {
                        return {
                            color: "#ce06cb",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "A" || feature.properties.route_id === "C" || feature.properties.route_id === "E" || feature.properties.route_id === "SI" || feature.properties.route_id === "H") {
                        return {
                            color: "#fd9a00",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "Air") {
                        return {
                            color: "#ffff00",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "B" || feature.properties.route_id === "D" || feature.properties.route_id === "F" || feature.properties.route_id === "M") {
                        return {
                            color: "#ffff00",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "G") {
                        return {
                            color: "#9ace00",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "FS" || feature.properties.route_id === "GS") {
                        return {
                            color: "#6e6e6e",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "J" || feature.properties.route_id === "Z") {
                        return {
                            color: "#976900",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "L") {
                        return {
                            color: "#969696",
                            weight: 3,
                            opacity: 1
                        };
                    };
                    if (feature.properties.route_id === "N" || feature.properties.route_id === "Q" || feature.properties.route_id === "R") {
                        return {
                            color: "#ffff00",
                            weight: 3,
                            opacity: 1
                        };
                    };
                },
                onEachFeature: function (feature, layer) {
                    if (feature.properties) {
                        var content =   "<table class='table table-striped table-bordered table-condensed'>"+
                                            "<tr><th>Division</th><td>" + feature.properties.Division + "</td></tr>"+
                                            "<tr><th>Line</th><td>" + feature.properties.Line + "</td></tr>"+
                                        "<table>";
                        if (document.body.clientWidth <= 767) {
                            layer.on({
                                click: function(e) {
                                    $("#feature-title").html(feature.properties.Line);
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
                    }
                    layer.on({
                        mouseover: function(e) {
                            var layer = e.target;
                            layer.setStyle({
                                weight: 3,
                                color: "#00FFFF",
                                opacity: 1
                            });
                            if (!L.Browser.ie && !L.Browser.opera) {
                                layer.bringToFront();
                            }
                        },
                        mouseout: function(e) {
                            subwayLines.resetStyle(e.target);
                        }
                    });
                }
            });
            $.getJSON("data/subways.geojson", function (data) {
                subwayLines.addData(data);
            });

            var theaters = L.geoJson(null, {
                pointToLayer: function (feature, latlng) {
                    return L.marker(latlng, {
                        icon: L.icon({
                            iconUrl: "img/theater.png",
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
                                            "<tr><th>Address</th><td>" + feature.properties.ADDRESS1 + "</td></tr>"+
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
                        theaterSearch.push({
                            value: layer.feature.properties.NAME,
                            tokens: [layer.feature.properties.NAME],
                            layer: "Theaters",
                            id: L.stamp(layer),
                            lat: layer.feature.geometry.coordinates[1],
                            lng: layer.feature.geometry.coordinates[0]
                        });
                    }
                }
            });
            $.getJSON("data/DOITT_THEATER_01_13SEPT2010.geojson", function (data) {
                theaters.addData(data);
            });

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
        </script>
    </body>
</html>
