<html>
    <head>
        <link rel="stylesheet" href="/lib/leaflet-0.5.1/dist/leaflet.css" />
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="/lib/leaflet-0.5.1/dist/leaflet.ie.css" />
        <![endif]-->
        <script src="/lib/leaflet-0.5.1/dist/leaflet.js"></script>
        <style type="text/css">
            #map { right: 10px; top: 10px; left: 10px; bottom: 10px; position: absolute; }
        </style>
    </head>
    <body>
        <div id="map"></div>

        <script type="text/javascript">

        var map = L.map('map').setView([18, 102], 6);
        L.tileLayer('/tms/1.0.0/topomap/{z}/{x}/{y}.jpeg',
        {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © Landsat 8',
    maxZoom: 16,
    tms: true
}).addTo(map);
        </script>

    </body>
</html>
