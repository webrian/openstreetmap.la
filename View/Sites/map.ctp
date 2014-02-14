<div class="row" id="container">
    <div class="col-sm-3 col-lg-3" id="sidebar" style="padding: 10px; overflow: auto;">

        <!-- map layer toggle button group -->
        <div class="btn-group" style="margin-bottom: 10px;">
            <button type="button" class="btn btn-default active" id="streets-button">Streets</button>
            <button type="button" class="btn btn-default" id="hybrid-button">Hybrid</button>
        </div>

        <div class="input-group search-container" style="margin-bottom: 10px; width: 100%;">
            <input id="searchbox" type="text" class="form-control" placeholder="Search">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
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
if ($lang == "lo") {
    $osmtoolsUrl = "http://{s}.laostile.osm-tools.org/osm_th/{z}/{x}/{y}.png";
} else {
    $osmtoolsUrl = "http://{s}.laostile.osm-tools.org/osm_en/{z}/{x}/{y}.png";
}


echo $this->Html->scriptBlock("var osmtoolsUrl = \"$osmtoolsUrl\";", array('safe' => false));

// Include jquery and bootstrap JavaScript file
echo $this->Html->script(array('jquery-1.10.2.min.js', 'bootstrap-3.0.3/bootstrap.min.js', 'typeahead.bundle.min.js', 'leaflet-0.5.1/leaflet.js'));


$date = date_create();
if (Configure::read("debug") == 0) {
    echo $this->Html->script("map.min.js");
} else {
    echo $this->Html->script("map.js?_dc=" . date_timestamp_get($date));
}
?>
