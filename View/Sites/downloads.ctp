<div class="container" style="margin-top: 20px">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            On this page OpenStreetMap map data in different
            file formats for Laos and Cambodia are provided. Since OpenStreetMap is a work
            in progress made by volunteers the data may be dated or inclompete.
            The data on this page has not been checked or verified by OpenStreetMap.la.
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            Other file formats or data extracts can be provided on
            request. Please contact info <i>at</i> openstreetmap.la
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            All these files are licensed under the terms of the
            <a class="external" href="http://opendatacommons.org/licenses/odbl/summary/">
                Open Data Commons Open Database License</a>
            license. If you use these data please attribute the OpenStreetMap
            contributors by including a link to www.openstreetmap.org. If you alter, transform,
            or build upon this work, you may distribute the resulting work only under the same
            or similar license to this one.
        </div>
    </div>
    <?php
    // Laos files and download table
    $laos_files = array(
        array("laos.osm.pbf", "OSM Protobuf", __("Complete database")),
        array("laos.osm.bz2", "OSM XML", __("Complete database")),
        array("amenities.shp.zip", "ESRI Shapefile", __("Amenities")),
        array("buildings.shp.zip", "ESRI Shapefile", __("Buildings")),
        array("country.shp.zip", "ESRI Shapefile", __("Country")),
        array("national_parks.shp.zip", "ESRI Shapefile", __("National Parks")),
        array("places.shp.zip", "ESRI Shapefile", __("Places")),
        array("provinces.shp.zip", "ESRI Shapefile", __("Provinces")),
        array("roads.shp.zip", "ESRI Shapefile", __("Roads")),
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
        array("amenities.shp.zip", "ESRI Shapefile", __("Amenities")),
        array("buildings.shp.zip", "ESRI Shapefile", __("Buildings")),
        array("country.shp.zip", "ESRI Shapefile", __("Country")),
        array("national_parks.shp.zip", "ESRI Shapefile", __("National Parks")),
        array("places.shp.zip", "ESRI Shapefile", __("Places")),
        array("provinces.shp.zip", "ESRI Shapefile", __("Provinces")),
        array("roads.shp.zip", "ESRI Shapefile", __("Roads")),
        array("waterway_lines.shp.zip", "ESRI  Shapefile", __("Waterways")),
        array("waterway_polygons.shp.zip", "ESRI Shapefile", __("Waterbodies")),
        array("gmapsupp.img.zip", "<a class=\"external\" href=\"http://wiki.openstreetmap.org/wiki/OSM_Map_On_Garmin#Installing_the_map_onto_your_GPS\">Garmin Map</a>", __("Routable GPS map")),
        array("pointsofinterest.kmz", "KMZ Google Earth", __("Points of Interest")),
        array("pointsofinterest.gpx.zip", "GPX", __("Points of Interest")));

    fileList(__('Cambodia'), $cambodia_files, 'Cambodia');

    function fileList($country, $files, $downloadDirectory) {
        echo "<div class=\"row\"><div class=\"col-md-8 col-md-offset-2\"><h2>$country</h2></div></div>";
        echo "<div class=\"row\"><div class=\"col-md-2 col-md-offset-2\">" . __('Content') . "</div><div class=\"col-md-2\">Format</div><div class=\"col-md-2\">" . __('Download Size') . "</div></div>\n";

        foreach ($files as $f) {

            echo "<div class=\"row\"><div class=\"col-md-2 col-md-offset-2\"><a href=\"/downloads/" . strtolower($downloadDirectory);
            echo "/$f[0]\">$f[2]</a></div><div class=\"col-md-2\">$f[1]</div><div class=\"col-md-2\">";
            $path = dirname(APP) . DS . "Data" . DS . $downloadDirectory;
            echo formatBytes(@filesize($path . DS . $f[0])) . "</div></div>\n";
        }

        echo "<div class=\"row\"><div class=\"col-md-8 col-md-offset-2\">" . __('Last data update') . ": ";
        echo lastModified($path . DS . $files[0][0]);
        echo "</div></div>";
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
<?php
// Include jquery and bootstrap JavaScript file
    echo $this->Html->script(array('jquery-1.10.2.min.js', 'bootstrap-3.0.3/bootstrap.min.js'));
?>