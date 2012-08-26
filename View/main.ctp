<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="google-site-verification" content="cXvQfuOySAHBWbH1EvKsUGZk5S7_nU2f_lcG2rqZrr0" />
        <title>OpenStreetMap ລາວ</title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <!-- ** CSS ** -->
        <!-- base library -->
        <link rel="stylesheet" type="text/css" href="/lib/ext-3.3.0/resources/css/ext-all-notheme.css"/>
        <link rel="stylesheet" type="text/css" href="/lib/ext-3.3.0/resources/css/xtheme-gray.css"/>
        <link rel="stylesheet" href="/lib/leaflet/dist/leaflet.css" />
        <!--[if lte IE 8]><link rel="stylesheet" href="lib/leaflet/dist/leaflet.ie.css" /><![endif]-->
        <link rel="stylesheet" type="text/css" href="/style.css"/>

        <!-- ** Javascript ** -->
        <!-- ExtJS library: base/adapter -->
        <script type="text/javascript" src="/lib/ext-3.3.0/adapter/ext/ext-base.js"></script>

        <!-- ExtJS library: all widgets -->
        <script type="text/javascript" src="/lib/ext-3.3.0/ext-all-osmla.js"></script>

        <script src="/lib/leaflet/dist/leaflet.js" type="text/javascript"></script>

        <!-- page specific -->
        <script type="text/javascript">
<?php
echo "var initLang='$lang';";

echo "var initCenter=new L.LatLng($lat, $lng);";
if (!empty($mlat) && !empty($mlng)) {
    echo "var initMarker=new L.LatLng($mlat, $mlng);";
} else {
    echo "var initMarker=null;";
}
echo "var initZoom=$zoom;";


if (!empty($startCoords)) {
    echo "var initStart=new L.LatLng($startCoords[0], $startCoords[1]);";
} else {
    echo "var initStart=null;";
}
if (!empty($destCoords)) {
    echo "var initDest=new L.LatLng($destCoords[0], $destCoords[1]);";
} else {
    echo "var initDest=null;";
}
if (!empty($viaCoords) && count($viaCoords) > 0) {
    echo "var initVias=[";
    for ($i = 0; $i < count($viaCoords); $i++) {
        $viaCoord = $viaCoords[$i];
        echo "new L.LatLng($viaCoord[0], $viaCoord[1])";
        if ($i < (count($viaCoords) - 1)) {
            echo ",";
        }
    }
    echo "];";
} else {
    echo "var initVias=null;";
}
echo "\n";
echo "Ext.namespace('Ext.ux');";
echo "Ext.ux.activeTab = '$tab';\n";
?>
        </script>
        <script type="text/javascript" src="/main.js"></script>

        <!-- google analytics -->
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-24897786-1']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
        </script>
    </head>
    <body>
        <div id="sidepanel-header" style="text-align: center; padding: 5px; padding-top: 10px;">
            <img src="/img/osmla.png" alt="OpenStreetMap Laos" height="118" width="118" /><br/>
            <h1>OpenStreetMap.la</h1>
            <div style="padding: 10px 0px 10px;">
                The free wiki world map
            </div>
        </div>
        <div id="route-summary-panel" style="padding: 5px;">
            <!-- placeholder -->
        </div>
        <div id="route-result-panel" style="padding: 5px">
            <!-- placeholder -->
        </div>

        <div id="map" style="width: 100%; height: 100%">

        </div>
        <div id="edit-tab" class="x-panel-mc">
            <div class="main-tab">
                <h1>Edit the map</h1>
                <div class="content">
                    Help improve the map! If you find inaccuracies, missing roads
                    or points of interest,
                </div>
                <div id="edit-link" class="content">
                    edit the map
                </div>
                <div class="content">
                    on the main page openstreetmap.org using the
                    <a class="external" href="http://wiki.openstreetmap.org/wiki/Potlatch">Potlatch</a> editor.
                </div>
                <div class="content">
                    <b>Note:</b> Requires a login from openstreetmap.org.
                </div>
            </div>
        </div>
        <div id="downloads-tab" class="x-panel-mc">
            <div class="main-tab">
                <h1>Downloads</h1>
                <div class="content">
                    On this page OpenStreetMap map data in different
                    file formats for Laos and Cambodia are provided. Since OpenStreetMap is a work
                    in progress made by volunteers the data may be dated or inclompete.
                    The data on this page has not been checked or verified by OpenStreetMap.la.
                </div>
                <div class="content">
                    Other file formats or data extracts can be provided on
                    request. Please contact info <i>at</i> openstreetmap.la
                </div>
                <div class="content">
                    All these files are licensed under the terms of the
                    <a href="http://creativecommons.org/licenses/by-sa/2.0/">Creative Commons Attribution Share-Alike 2.0</a>
                    license. If you use these data please attribute the OpenStreetMap
                    contributors by including a link to www.openstreetmap.org. If you alter, transform,
                    or build upon this work, you may distribute the resulting work only under the same
                    or similar license to this one. 
                </div>

<?php
// Laos files and download table
$laos_files = array(
    array("laos.osm.pbf", "OSM Protobuf", "Complete database"),
    array("laos.osm.bz2", "OSM XML", "Complete database"),
    array("roads.shp.zip", "ESRI Shapefile", "Roads"),
    array("amenities.shp.zip", "ESRI Shapefile", "Amenities"),
    array("places.shp.zip", "ESRI Shapefile", "Places"),
    array("waterway_lines.shp.zip", "ESRI  Shapefile", "Waterways"),
    array("waterway_polygons.shp.zip", "ESRI Shapefile", "Waterbodies"),
    array("gmapsupp.img.zip", "<a class=\"external\" href=\"http://wiki.openstreetmap.org/wiki/OSM_Map_On_Garmin#Installing_the_map_onto_your_GPS\">Garmin Map</a>", "Routable GPS map"),
    array("pointsofinterest.kmz", "KMZ Google Earth", "Points of Interest"),
    array("pointsofinterest.gpx.zip", "GPX", "Points of Interest"));

fileList('Laos', $laos_files);

// Cambodia files and download table
$cambodia_files = array(
    array("cambodia.osm.pbf", "OSM Protobuf", "Complete database"),
    array("cambodia.osm.bz2", "OSM XML", "Complete database"),
    array("roads.shp.zip", "ESRI Shapefile", "Roads"),
    array("amenities.shp.zip", "ESRI Shapefile", "Amenities"),
    array("places.shp.zip", "ESRI Shapefile", "Places"),
    array("waterway_lines.shp.zip", "ESRI  Shapefile", "Waterways"),
    array("waterway_polygons.shp.zip", "ESRI Shapefile", "Waterbodies"),
    array("gmapsupp.img.zip", "<a class=\"external\" href=\"http://wiki.openstreetmap.org/wiki/OSM_Map_On_Garmin#Installing_the_map_onto_your_GPS\">Garmin Map</a>", "Routable GPS map"),
    array("pointsofinterest.kmz", "KMZ Google Earth", "Points of Interest"),
    array("pointsofinterest.gpx.zip", "GPX", "Points of Interest"));

fileList('Cambodia', $cambodia_files);

function fileList($country, $files) {
    echo " <div class=\"content\"><h2>$country</h2><table width=\"100%\">";
    echo "<tr><td>Content</td><td>Format</td><td>Download Size</td></tr>\n";

    foreach ($files as $f) {

        echo "<tr><td><a href=\"/downloads/" . strtolower($country);
        echo "/$f[0]\">$f[2]</a></td><td>$f[1]</td><td>";
        $path = dirname(APP) . DS . "Data" . DS . $country;
        echo formatBytes(@filesize($path . DS . $f[0])) . "</td></tr>\n";
    }

    echo "</table></div>";

    echo "<div class=\"content\">Last data update: ";
    echo lastModified($path . DS . $files[0][0]);
    echo "</div>";
}

function formatBytes($bytes) {
    if ($bytes < 1024)
        return $bytes . ' B';
    elseif ($bytes < 1048576)
        return round($bytes / 1024, 2) . ' KB';
    else
        return round($bytes / 1048576, 2) . ' MB';
}

function lastModified($file) {
    if (file_exists($file)) {
        return date("j. M Y", filemtime($file));
    } else {
        return "unknown";
    }
}
?>
            </div>
        </div>
        <div id="about-tab" class="x-panel-mc">
            <div class="main-tab">
                <h1>About OpenStreetMap.la</h1>
                <div class="content">
                    OpenStreetMap ແມ່ນລະບົບແຜນທີ່ຂອງທົ່ວໂລກ ທີ່ສາມາດນຳໃຊ້ໄດ້ ຟຣີ ແລະ ສາມາດແກ້ໄຂໄດ້ ມັນໄດ້ຖືກສ້າງຂຶ້ນໂດຍອາສາສະຫມັກແບບທ່ານ
        	OpenStreetMap ແມ່ນອານຸຍາດໃຫ້ທ່ານ ເບິ່ງ, ແກ້ໄຂ ແລະ ນຳໃຊ້ ຂໍ້ມູນທາງພູມສາດ ໃນຮູບແບບລວມສູນ ຈາກທຸກໆບ່ອນໃນໂລກ
			ສຳລັບເວັບໄຊ້ແຫ່ງນີ້ ແມ່ນນຳສະເຫນີແຜນທີ່ ຂອງປະເທດລາວ ເປັນທັງ ພາສາລາວ ແລະ ອັງກິດ ພ້ອມນັ້ນຍັງໃຫ້ທ່ານສາມາດ ດາວໂຫລດໄຟລຂໍ້ມູນໃນຮູບແບບຕ່າງໆໄດ້ອີກ
			ທ່ານສາມາດຫາຂໍ້ມູນເພິ່ມເຕີມກ່ຽວກັບ OpenStreetMap ແລະ ວິທີການເຂົ້າຮ່ວມເປັນຜູ້ສ້າງຂໍ້ມູນໄດ້ທີ່ 
                    <a class="external" href="http://wiki.openstreetmap.org/wiki/Main_Page">OpenStreetMap Wiki</a>.
                </div>
                <div class="content">
                    OpenStreetMap is a free editable map of the whole world. It
                    is made by people like you.
                    OpenStreetMap allows you to view, edit and use geographical
                    data in a collaborative way from anywhere on Earth.
                    This web page provides maps in Lao and English language as
                    well as downloads in different file formats.
                    Please find more information about OpenStreetMap and how to join on the
                    <a class="external" href="http://wiki.openstreetmap.org/wiki/Main_Page">OpenStreetMap Wiki</a>.
                </div>
                <div class="content">
                    OpenStreetMap.la is kindly hosted by
                    <a class="external" href="http://www.laowebhosting.com/">
                        Lao-Webhosting
                    </a>.
                </div>
                <div class="content">
                    <a href="http://validator.w3.org/check?uri=referer">
                        <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
