<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="google-site-verification" content="cXvQfuOySAHBWbH1EvKsUGZk5S7_nU2f_lcG2rqZrr0" />
        <meta name="msvalidate.01" content="E23E53812CB2D24F8FC5D93E0828007D" />
        <title><?php echo __('OpenStreetMap Laos'); ?></title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <!-- ** CSS ** -->
        <!-- base library -->
        <link rel="stylesheet" type="text/css" href="/lib/ext-3.3.0/resources/css/ext-all-notheme.css"/>
        <link rel="stylesheet" type="text/css" href="/lib/ext-3.3.0/resources/css/xtheme-gray.css"/>
        <link rel="stylesheet" href="/lib/leaflet-0.4.4/dist/leaflet.css" />
        <!--[if lte IE 8]><link rel="stylesheet" href="/lib/leaflet-0.4.4/dist/leaflet.ie.css" /><![endif]-->
        <link rel="stylesheet" type="text/css" href="/style.css"/>

        <!-- ** Javascript ** -->
        <!-- ExtJS library: base/adapter -->
        <script type="text/javascript" src="/lib/ext-3.3.0/adapter/ext/ext-base.js"></script>

        <!-- ExtJS library: all widgets -->
        <script type="text/javascript" src="/lib/ext-3.3.0/ext-all-osmla.js"></script>

        <script src="/lib/leaflet-0.4.4/dist/leaflet.js" type="text/javascript"></script>

        <script src="/lang?_dc=<?php echo time(); ?>" type="text/javascript"></script>

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
    </head>
    <body>
        <div id="sidepanel-header" style="text-align: center; padding: 5px; padding-top: 10px;">
            <img src="/img/osmla.png" alt="<?php echo __('OpenStreetMap Laos'); ?>" height="118" width="118" /><br/>
            <h1>OpenStreetMap.la</h1>
            <div style="padding: 10px 0px 10px;">
                <?php echo __('The free wiki world map'); ?>
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
                <h1><?php echo __("Edit the map"); ?></h1>
                <?php
                // Include the text about how to edit the map
                echo $this->element("Sites/" . Configure::read('Config.language') . "/edit");
                ?>
            </div>
        </div>
        <div id="downloads-tab" class="x-panel-mc">
            <div class="main-tab">
                <h1><?php echo __('Downloads'); ?></h1>
                <?php
                // Include the introductory text for the downloads
                echo $this->element("Sites/" . Configure::read('Config.language') . "/downloads");

                // Laos files and download table
                $laos_files = array(
                    array("laos.osm.pbf", "OSM Protobuf", __("Complete database")),
                    array("laos.osm.bz2", "OSM XML", __("Complete database")),
                    array("roads.shp.zip", "ESRI Shapefile", __("Roads")),
                    array("amenities.shp.zip", "ESRI Shapefile", __("Amenities")),
                    array("places.shp.zip", "ESRI Shapefile", __("Places")),
                    array("waterway_lines.shp.zip", "ESRI  Shapefile", __("Waterways")),
                    array("waterway_polygons.shp.zip", "ESRI Shapefile", __("Waterbodies")),
                    array("gmapsupp.img.zip", "<a class=\"external\" href=\"http://wiki.openstreetmap.org/wiki/OSM_Map_On_Garmin#Installing_the_map_onto_your_GPS\">Garmin Map</a>", __("Routable GPS map")),
                    array("pointsofinterest.kmz", "KMZ Google Earth", __("Points of Interest")),
                    array("pointsofinterest.gpx.zip", "GPX", __("Points of Interest")));

                fileList(__('Laos'), $laos_files, 'Laos');

                // Cambodia files and download table
                $cambodia_files = array(
                    array("cambodia.osm.pbf", "OSM Protobuf", __("Complete database")),
                    array("cambodia.osm.bz2", "OSM XML", __("Complete database")),
                    array("roads.shp.zip", "ESRI Shapefile", __("Roads")),
                    array("amenities.shp.zip", "ESRI Shapefile", __("Amenities")),
                    array("places.shp.zip", "ESRI Shapefile", __("Places")),
                    array("waterway_lines.shp.zip", "ESRI  Shapefile", __("Waterways")),
                    array("waterway_polygons.shp.zip", "ESRI Shapefile", __("Waterbodies")),
                    array("gmapsupp.img.zip", "<a class=\"external\" href=\"http://wiki.openstreetmap.org/wiki/OSM_Map_On_Garmin#Installing_the_map_onto_your_GPS\">Garmin Map</a>", __("Routable GPS map")),
                    array("pointsofinterest.kmz", "KMZ Google Earth", __("Points of Interest")),
                    array("pointsofinterest.gpx.zip", "GPX", __("Points of Interest")));

                fileList(__('Cambodia'), $cambodia_files, 'Cambodia');

                function fileList($country, $files, $downloadDirectory) {
                    echo " <div class=\"content\"><h2>$country</h2><table width=\"100%\">";
                    echo "<tr><td>" . __('Content') . "</td><td>Format</td><td>" . __('Download Size') . "</td></tr>\n";

                    foreach ($files as $f) {

                        echo "<tr><td><a href=\"/downloads/" . strtolower($downloadDirectory);
                        echo "/$f[0]\">$f[2]</a></td><td>$f[1]</td><td>";
                        $path = dirname(APP) . DS . "Data" . DS . $downloadDirectory;
                        echo formatBytes(@filesize($path . DS . $f[0])) . "</td></tr>\n";
                    }

                    echo "</table></div>";

                    echo "<div class=\"content\">" . __('Last data update') . ": ";
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
                        return __("unknown");
                    }
                }
                ?>
            </div>
        </div>
        <div id="about-tab" class="x-panel-mc">
            <div class="main-tab">
                <h1><?php echo __("About OpenStreetMap.la"); ?></h1>
                <?php
                // Include the about text
                echo $this->element("Sites/" . Configure::read('Config.language') . "/about");
                ?>
                <div class="content">
                    <a href="http://validator.w3.org/check?uri=referer">
                        <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
