<?php

include 'geohash.class.php';

class SitesController extends AppController {
    //public $components = array('RequestHandler');

    /**
     * Associative array of two-letter language keys to three-letter langague
     * keys.
     * @var array
     */
    private $_languages = array(
        'lo' => 'lo_LA',
        'en' => 'en_US'
    );
    private $_languageCode = null;
    private $_languageCookie = '__LANG__';
    private $_cookieIsEncrypted = false;
    private $_expiration = '30 Days';

    public function beforeFilter() {

        $lang = $this->_extractLanguage();
        $this->set('lang', $lang);

        $this->Session->write('Config.language', $this->_languages[$lang]);
        $this->_languageCode = $lang;
        Configure::write('Config.language', $this->Session->read('Config.language'));

        // Try to extract the browser
        //if($this->RequestHandler->isMobile()){
        //$this->viewPath = 'Mobile';
        //}
    }

    public function main($tab = 'map') {

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));

        // Check first if the requested tab is valid (should always be true...)
        if (in_array($tab, array('map', 'edit', 'downloads', 'about'))) {
            $this->set('tab', $tab);
        } else {
            throw new NotFoundException();
        }

        $geohash = new Geohash();

        // Checks if a Cookie is set. Expects whitespace separated longitude
        // latitude and zoom level.
        $initLocations = explode(" ", $this->Cookie->read('__LOCATION__'));

        // If a Cookie is set, take the coordinates from the cookie, else
        // set some default values
        if (isset($initLocations[0]) && $initLocations[0] != NULL) {
            $lng = $initLocations[0];
        } else {
            $lng = 103.75;
        }
        if (isset($initLocations[1]) && $initLocations[1] != NULL) {
            $lat = $initLocations[1];
        } else {
            $lat = 18.25;
        }
        if (isset($initLocations[2]) && $initLocations[2] != NULL) {
            $zoom = $initLocations[2];
        } else {
            $zoom = 6;
        }

        // Check first if marker position is set but no center coordinates
        if (isset($this->request->query['mlat']) && !isset($this->request->query['lat'])) {
            $mlat = $this->request->query['mlat'];
            // Set the center coordinates to the marker
            $lat = $this->request->query['mlat'];
            $this->set('mlat', $mlat);
        } elseif (isset($this->request->query['mlat']) && isset($this->request->query['lat'])) {
            $mlat = $this->request->query['mlat'];
            // Set the center coordinates to the marker
            $lat = $this->request->query['lat'];
            $this->set('mlat', $mlat);
        } else {
            if (isset($this->request->query['lat'])) {
                $lat = $this->request->query['lat'];
            }
        }
        $this->set('lat', $lat);

        // Check first if marker position is set but no center coordinates
        if (isset($this->request->query['mlon']) && !isset($this->request->query['lon'])) {
            $mlng = $this->request->query['mlon'];
            // Set the center coordinates to the marker
            $lng = $this->request->query['mlon'];
            $this->set('mlng', $nlng);
        } elseif (isset($this->request->query['mlon']) && isset($this->request->query['lon'])) {
            $mlng = $this->request->query['mlon'];
            // Set the center coordinates to the marker
            $lng = $this->request->query['lon'];
            $this->set('mlng', $mlng);
        } elseif (isset($this->request->query['lon'])) {
            $lng = $this->request->query['lon'];
        }
        $this->set('lng', $lng);

        // Do anyway the zoom
        if (isset($this->request->query['zoom'])) {
            $zoom = $this->request->query['zoom'];
        }
        $this->set('zoom', $zoom);

        // Check also routing start and destination
        if (isset($this->request->query['start'])) {
            $start = $this->request->query['start'];
            $startCoords = $geohash->decode($start);
            $this->set('startCoords', $startCoords);
        }
        if (isset($this->request->query['dest'])) {
            $dest = $this->request->query['dest'];
            $destCoords = $geohash->decode($dest);
            $this->set('destCoords', $destCoords);
        }
        if (isset($this->request->query['via'])) {
            $via = $this->request->query['via'];
            $vias = explode(',', $via);
            $viaCoords = array();
            foreach ($vias as $v) {
                array_push($viaCoords, $geohash->decode($v));
            }
            $this->set('viaCoords', $viaCoords);
        }

        // Get the current url
        $hereUrl = $this->request->here;

        // Append a slash at the end of the current url if it does not end with
        // one.
        if(!eregi("\/$", $hereUrl)){
            $hereUrl .= '/';
        }

        $hereUrl .= '?lang=' . $this->_languageCode;

        // Log a successful access
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $hereUrl,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => 3266
        );
        CakeLog::write("apache_access", $message);
    }

    /**
     * Extract the requested language. The language is decided in the following
     * order:
     * 1. valid parameter lang set in GET request
     * 2. valid parameter __LANG__ set in cookie
     * 3. lao is preferred language
     * @return String Two-letter language identifier
     */
    private function _extractLanguage() {
        // Default lanuage is always Lao
        $lang = 'lo';

        // Set the cookie name
        $this->Cookie->name = '_osm_la';

        // First check if the language parameter is set in the URL. The URL
        // parameter has first priority.
        if (isset($this->request->query['lang'])) {
            $param = $this->request->query['lang'];
            // Check if the URL parameter is a valid language identifier
            if (array_key_exists($param, $this->_languages)) {
                // Set the language to the URL parameter
                $lang = $param;
            }
        } else if ($this->Cookie->read($this->_languageCookie) != null) {
            // Check if a cookie is set and set its value as language. A Cookie
            // has second priority
            $cookieValue = $this->Cookie->read($this->_languageCookie);
            // Check if the URL parameter is a valid language identifier
            if (array_key_exists($cookieValue, $this->_languages)) {
                // Set the language to the Cookie value
                $lang = $cookieValue;
            }
        }

        // If neither the lang parameter nor a cookie is set, set and return
        // Lao as language.
        $this->Cookie->write($this->_languageCookie, $lang, $this->_cookieIsEncrypted, $this->_expiration);
        return $lang;
    }

}

?>
