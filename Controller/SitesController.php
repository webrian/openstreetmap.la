<?php

include 'geohash.class.php';

class SitesController extends AppController {

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

        // Try to fetch suburls with a trailing slash and redirect them
        /*if (preg_match("/[a-zA-Z]+\/$/", $this->request->here) > 0) {
            $this->redirect("/$tab", $tab);
        }*/

        $geohash = new Geohash();

        // Set the standard values
        $lat = 18.25;
        $lng = 103.75;
        $zoom = 6;

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

        // Check the language
        $lang = 'lo';
        if (isset($this->request->query['lang'])) {
            if ($this->request->query['lang'] == 'en') {
                $lang = 'en';
            }
        }
        $this->set('lang', $lang);

        // Log a successful download
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => " - "
        );
        CakeLog::write("apache_access", $message);

        $this->render('/main');
    }

}

?>
