<?php

require 'geohash.class.php';

class RoutesController extends AppController {

    public function index() {
        $this->response->type('json');

        // Get the start, destination and via points as geohashs
        $geohash = new Geohash();
        $start = $geohash->decode($this->request->query['start']);
        $dest = $geohash->decode($this->request->query['dest']);

        if (isset($this->request->query['via']) && $this->request->query['via'] != NULL) {
            $viaHashs = explode(',', $this->request->query['via']);
        }

        // Compose the request URL
        $requestUrl = "http://openstreetmap.la:4576/viaroute&start=" . $start[0] . "," . $start[1] . "&dest=" . $dest[0] . "," . $dest[1] . "&output=json&instructions=true";

        // Append the via points if requested in the following
        // format: &via=18,102.6&via=17.9,102.59
        if (!empty($viaHashs)) {
            foreach ($viaHashs as $via) {
                $viaCoordinates = $geohash->decode($via);
                $requestUrl .= "&via=" . $viaCoordinates[0] . "," . $viaCoordinates[1];
            }
        }

        //echo $requestUrl;

        $content = @file_get_contents($requestUrl);

        if (empty($content)) {
            throw new BadRequestException('');
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

        $this->render('/json');
    }

}
?>
