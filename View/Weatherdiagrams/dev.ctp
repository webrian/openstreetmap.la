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
        <script type="text/javascript" src="/lib/extjs-4.1.1/ext-debug.js"></script>
        <script type="text/javascript">
            Ext.Loader.setConfig({
                enabled: true,
                paths: {
                    'Osm': '/lib/app',
                    'Ext': '/lib/extjs-4.1.1/src'
                }
            });
        </script>
        <script type="text/javascript" src="/lib/app/weather.js"></script>
        <style type="text/css" >
            a.external {
                background: url("/img/external-link-ltr-icon.png") no-repeat scroll right center transparent;
                padding:0 13px 0 0;
            }
        </style>
    </head>
    <body>
        <!-- Loading spinner that is faded out after all JavaScript is loaded -->
        <div id="loading-mask" style="width: 100%; height: 100%;">
            <div style="position: absolute; top: 50%; right: 50%">
                <img src="/img/spinner.gif" alt="<?php echo __('Loading ...'); ?>"/>
            </div>
        </div>
        <div id="header-div" style="padding: 3px;">
            <h1>Weather Diagrams</h1>
            <div>
                Weather diagrams for Vientiane based on
                <a class="external" href="https://en.wikipedia.org/wiki/METAR">
                    METAR data
                </a>.
                Data samples are collected every 30 minutes between 6.00 am and 9.00 pm.
                Collection dates back to the 1<sup>st</sup> of January 2003.
            </div>
            <div>
                A full database extract can be downloaded from
                <a href="http://<?php echo $host; ?>/downloads/laos/metar_vlvt.bz2">/downloads/laos/metar_vlvt.bz2</a>
                (<?php echo $filesize; ?>).
            </div>
        </div>
        <div id="intro-div">
            <div>
                Detailed weather information for a selected date.
            </div>
            <div>
                Pick a date in the calendar below:
            </div>
        </div>
        <div id="temperature-trends-div">
            <div>
                This diagram shows minimum, maximum and average temperature per
                day for a selected time span. If the time span is longer than
                half a year, temperature values are aggregated to weeks.
            </div>
            <div>
                Pick start and end date in the calendars below and update the chart.
            </div>
        </div>
    </body>
</html>
