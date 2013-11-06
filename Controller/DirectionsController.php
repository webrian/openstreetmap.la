<?php

require 'geohash.class.php';

class DirectionsController extends AppController {

    public function beforeFilter() {
        $this->response->type('json');
    }

    public function index() {

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));

        // Get the start, destination and via points as geohashs
        $geohash = new Geohash();
        $start = $geohash->decode($this->request->query['start']);
        $dest = $geohash->decode($this->request->query['dest']);

        if (isset($this->request->query['via']) && $this->request->query['via'] != NULL) {
            $viaHashs = explode(',', $this->request->query['via']);
        }

        // Compose the request URL
        $requestUrl = "http://localhost:8118/viaroute?loc=" . $start[0] . "," . $start[1];
        if (!empty($viaHashs)) {
            foreach ($viaHashs as $via) {
                $viaCoordinates = $geohash->decode($via);
                $requestUrl .= "&loc=" . $viaCoordinates[0] . "," . $viaCoordinates[1];
            }
        }
        $requestUrl .= "&loc=" . $dest[0] . "," . $dest[1] . "&output=json&instructions=true";

        //echo $requestUrl;

        $content = @file_get_contents($requestUrl);

        $this->set('data', json_decode($content));

        /**
          if (empty($content)) {
          // Log an error attempt
          $message = array(
          'clientIp' => $this->request->clientIp(),
          'method' => $this->request->method(),
          'here' => $this->request->here,
          'referer' => $this->request->referer(),
          'status' => 401,
          'filesize' => 0,
          'text' => "Server is not accessible."
          );
          CakeLog::write("apache_error", $message);
          throw new BadRequestException('Server is not accessible.');
          }

          $resultJson = json_decode($content);

          $geometry['type'] = "LineString";

          $coordinates = array();
          foreach ($resultJson->route_geometry as $coordinate) {
          array_push($coordinates, array($coordinate[1], $coordinate[0]));
          }
          $geometry['coordinates'] = $coordinates;
          $properties['status'] = $resultJson->status;
          $properties['status_message'] = $resultJson->status_message;
          $properties['total_distance'] = $resultJson->route_summary->total_distance;
          $properties['total_time'] = $resultJson->route_summary->total_time;
          $properties['transactionId'] = $resultJson->transactionId;
          $properties['route_instructions'] = $resultJson->route_instructions;
          //$properties['request_url'] = $requestUrl;

          $feature['geometry'] = $geometry;
          $feature['properties'] = $properties;
          $feature['type'] = 'Feature';

          $geoJson['type'] = "FeatureCollection";
          $geoJson['features'] = array($feature);

          $this->set('data', $geoJson);
         */

        // Log a successful found route
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => strlen(json_encode($geoJson))
        );
        CakeLog::write("apacihe_access", $message);
    }

}

?>
