<div class="row" id="container">
    <div class="col-sm-3 col-lg-3" id="sidebar" style="padding: 10px; overflow: auto;">

        <!-- map layer toggle button group -->
        <div class="btn-group" style="margin-bottom: 10px;">
            <button type="button" class="btn btn-default active" id="streets-button">
                <i class="icon-streets icon-lg"></i>&nbsp;&nbsp;Streets</button>
            <button type="button" class="btn btn-default" id="hybrid-button">
                <i class="icon-hybrid icon-lg"></i>&nbsp;&nbsp;Hybrid</button>
        </div>

        <div id="searchPanel" class="panel panel-default collapsible-panel">
            <div class="panel-heading">
                <a href="#" onclick="javascript:toggleDirectionsPanel('searchPanel')"><?php echo __("Find places"); ?></a>
            </div>
            <div class="panel-body hidden">
                <div class="input-group search-container" style="margin-bottom: 10px; width: 100%;">
                    <input id="searchbox" type="text" class="form-control" placeholder="<?php echo __("Enter village, amenity, shop, etc."); ?>">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

        <div id="directionsPanel" class="panel panel-default collapsible-panel">
            <div class="panel-heading">
                <a href="#" onclick="javascript:toggleDirectionsPanel('directionsPanel')">Get directions</a>
            </div>
            <div class="panel-body hidden">
                <div class="input-group search-container" style="margin-bottom: 10px; width: 100%;">
                    <input id="searchbox" type="text" class="form-control" placeholder="<?php echo __("Search places"); ?>">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <div class="input-group search-container" style="margin-bottom: 10px; width: 100%;">
                    <input id="searchbox" type="text" class="form-control" placeholder="<?php echo __("Search places"); ?>">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>

    </div>
    <div class="col-sm-9 col-lg-9" id="map">
        <!--div id="loading" style="display: block;">
            <div class="loading-indicator">
                <div class="progress progress-striped active">
                    <div class="progress-bar progress-bar-info" style="width: 100%"></div>
                </div>
            </div>
        </div-->
    </div>
</div>

<!--script type="text/javascript" src="assets/typeahead.js/typeahead.min.js"></script> <!--https://github.com/twitter/typeahead.js/-->

<?php
// Custom icon fonts
echo $this->Html->css(array("customfonts.css", "mapnik-icons.css", "map.css"));
// Include jquery and bootstrap JavaScript file
echo $this->Html->script(array('jquery-1.10.2.min.js', 'bootstrap-3.0.3/bootstrap.min.js', 'typeahead.bundle.min.js', 'leaflet-0.5.1/leaflet.js'));

if ($lang == "lo") {
    $osmtoolsUrl = "http://{s}.laostile.osm-tools.org/osm_th/{z}/{x}/{y}.png";
} else {
    $osmtoolsUrl = "http://{s}.laostile.osm-tools.org/osm_en/{z}/{x}/{y}.png";
}

$scriptBlock = "var osmtoolsUrl = \"$osmtoolsUrl\";";
$scriptBlock .= "var initLang='$lang';";
$scriptBlock .= "var initCenter=new L.LatLng($lat, $lng);";
if (!empty($mlat) && !empty($mlng)) {
    $scriptBlock .= "var initMarker=new L.LatLng($mlat, $mlng);";
} else {
    $scriptBlock .= "var initMarker=null;";
}
$scriptBlock .= "var initZoom=$zoom;";


if (!empty($startCoords)) {
    $scriptBlock .= "var initStart=new L.LatLng($startCoords[0], $startCoords[1]);";
} else {
    $scriptBlock .= "var initStart=null;";
}
if (!empty($destCoords)) {
    $scriptBlock .= "var initDest=new L.LatLng($destCoords[0], $destCoords[1]);";
} else {
    $scriptBlock .= "var initDest=null;";
}
if (!empty($viaCoords) && count($viaCoords) > 0) {
    $scriptBlock .= "var initVias=[";
    for ($i = 0; $i < count($viaCoords); $i++) {
        $viaCoord = $viaCoords[$i];
        $scriptBlock .= "new L.LatLng($viaCoord[0], $viaCoord[1])";
        if ($i < (count($viaCoords) - 1)) {
            $scriptBlock .= ",";
        }
    }
    $scriptBlock .= "];";
} else {
    $scriptBlock .= "var initVias=null;";
}
$scriptBlock .= "\n";

echo $this->Html->scriptBlock($scriptBlock);

$date = date_create();
if (Configure::read("debug") == 0) {
    echo $this->Html->script("map.min.js");
} else {
    echo $this->Html->script("map.js?_dc=" . date_timestamp_get($date));
}
?>
