<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head profile="http://a9.com/-/spec/opensearch/1.1/">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="google-site-verification" content="cXvQfuOySAHBWbH1EvKsUGZk5S7_nU2f_lcG2rqZrr0" />
        <meta name="msvalidate.01" content="E23E53812CB2D24F8FC5D93E0828007D" />
        <title><?php echo __('OpenStreetMap Laos'); ?></title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <!-- Link to the OpenSearch description document -->
        <link rel="search" type="application/opensearchdescription+xml"
              href="http://<?php echo $host; ?>/places.xml" title="OpenStreetMap.la Places" />

        <link rel="stylesheet" type="text/css" href="/lib/extjs-4.1.1/resources/css/ext-all-gray.css"></link>
        <script type="text/javascript" src="/lib/extjs-4.1.1/ext.js"></script>
        <!--script type="text/javascript" src="/lib/extjs-4.1.1/ext-all.js"></script-->
        <script type="text/javascript">
            Ext.Loader.setConfig({
                enabled: true,
                paths: {
                    'Osm': '/lib/app',
                    'Ext': '/lib/extjs-4.1.1/src'
                }
            });
        </script>
        <script type="text/javascript" src="/lib/app/weather-devel.js"></script>
        <!--script type="text/javascript" src="/app-all.js"></script-->
    </head>
    <body>
        <div id="header-div" style="padding: 3px;">
            <h1>Weather Diagrams</h1>
            <div>
                Weather diagrams for Vientiane based on <a href="https://en.wikipedia.org/wiki/METAR">METAR data</a>.
            </div>
            <div>
                Data collection dates back to 18<sup>th</sup> of January 2011.
            </div>
        </div>
    </body>
</html>