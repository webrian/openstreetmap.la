<?php echo $this->Html->docType("xhtml-strict"); ?>
<html lang="en">
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="description" content="OpenStreetMap Laos">
        <meta name="author" content="Adrian Weber">
        <title><?php echo __('OpenStreetMap Laos'); ?></title>

        <!-- Core CSS -->
        <?php
        echo $this->Html->css(array("bootstrap-3.0.3/bootstrap.min.css",
            "font-awesome-4.0.3/font-awesome.min.css",
            //"typeahead.js/typeahead.js-bootstrap.css",
            "leaflet-0.5.1/leaflet.css"));
        echo $this->fetch("css");
        ?>

        <!--Custom styles for this template-->
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
                -webkit-box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.5);
                -moz-box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.5);
                box-shadow: 0 -1px 10px rgba(0, 0, 0, 0.5);
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
                    display: none!important;
                }
            }
        </style>

        <!--HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries-->
        <!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.js"></script>
    <![endif]-->
    </head>

    <body>
        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle btn" data-toggle="collapse" data-target=".navbar-collapse" style="height: 34px; padding: 5px 10px; margin-right: 10px;"><i class="fa fa-ellipsis-v" style="color: white"></i></button>
                <?php
                if ($this->request->url == 'map') {
                    echo '<button id="toggle" type="button" class="navbar-toggle btn" style="height: 34px; padding: 5px 10px; margin-right: 10px;"><i id="toggleIcon" class="fa fa-check-square-o" style="color: white"></i></button>';
                }
                ?>
                <a class="navbar-brand" href="<?php echo $this->Html->url(array('controller' => 'sites', 'action' => 'main')); ?>">OpenStreetMap.la</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <?php
                        if ($lang == "en") {
                            $url = $this->Html->url(array($this->request->url, "?" => array("lang" => "lo")));
                            echo "<a href=\"$url\"><i class=\"fa fa-flag\"></i>&nbsp;&nbsp;ພາສາລາວ</a></li>";
                        } else {
                            $url = $this->Html->url(array($this->request->url, "?" => array("lang" => "en")));
                            echo "<a href=\"$url\"><i class=\"fa fa-flag\"></i>&nbsp;&nbsp;English</a></li>";
                        }
                        ?>
                    </li>
                </ul>
                <ul class="nav navbar-nav">
                    <!-- Edit menu -->
                    <li <?php
                        if ($this->request->url == "edit") {
                            echo "class=\"active\"";
                        }
                        ?>>
                        <a href="<?php echo $this->Html->url(array('controller' => 'sites', 'action' => 'main', 'edit')); ?>">
                            <i class="fa fa-pencil-square-o fa-lg" style="color: white"></i>&nbsp;&nbsp;<?php echo __('Edit'); ?>
                        </a>
                    </li>
                    <!-- Downloads menu -->
                    <li <?php
                        if ($this->request->url == "downloads") {
                            echo "class=\"active\"";
                        }
                        ?>>
                        <a href="<?php echo $this->Html->url(array('controller' => 'sites', 'action' => 'main', 'downloads')); ?>">
                            <i class="fa fa-cloud-download fa-lg" style="color: white"></i>&nbsp;&nbsp;<?php echo __('Downloads'); ?>
                        </a>
                    </li>
                    <!-- About menu -->
                    <li <?php
                        if ($this->request->url == "about") {
                            echo "class=\"active\"";
                        }
                        ?>>
                        <a href="<?php echo $this->Html->url(array('controller' => 'sites', 'action' => 'main', 'about')); ?>" data-toggle="collapse" data-target=".navbar-collapse.in">
                            <i class="fa fa-question-circle fa-lg" style="color: white"></i>&nbsp;&nbsp;<?php echo __('About this page'); ?>
                        </a>
                    </li>
                </ul>
            </div><!--/.navbar-collapse -->
        </div>

        <?php echo $this->fetch('content'); ?>

    </body>
</html>
