<html>
    <head>
        <link rel="stylesheet" href="/lib/leaflet-0.5.1/dist/leaflet.css" />
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="/lib/leaflet-0.5.1/dist/leaflet.ie.css" />
        <![endif]-->
        <script src="/lib/leaflet-0.5.1/dist/leaflet.js"></script>
        <style type="text/css">
            #map { height: 600px; width: 800px; }
        </style>
    </head>
    <body>
        <div id="map"></div>

        <script type="text/javascript">

        var map = L.map('map').setView([18, 102], 6);
        L.tileLayer('/tms/1.0.0/landsat8/{z}/{x}/{y}.jpeg',
        {
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © Landsat 8',
    maxZoom: 14,
    tms: true
}).addTo(map);
        </script>

    </body>
</html>
