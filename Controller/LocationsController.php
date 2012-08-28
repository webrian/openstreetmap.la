<?php

App::uses('Sanitize', 'Utility');

include 'geohash.class.php';

class LocationsController extends AppController {

    public function index() {

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));

        $this->response->type('json');

        $result = array();

        if (isset($this->request->query['q'])) {
            // Get the search string
            $search = Sanitize::clean($this->request->query['q']);

            // Query the database
            $data = $this->Location->find('all', array(
                        'conditions' => array("Location.name ~* '$search'"),
                        'limit' => 12
                    ));

            $geohash = new Geohash();

            // Create the result array
            $result = array();
            $result['data'] = array();
            $result['success'] = true;

            foreach ($data as $tuple) {

                $row = $tuple['Location'];
                $hash = $geohash->encode($row['lat'], $row['lon']);
                array_push($result['data'], array('name' => $row['name'], 'hash' => $hash, 'feature' => $row['feature']));
            }
        }

        $this->set('data', $result);

        // Log a successful download
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => strlen(json_encode($result))
        );
        CakeLog::write("apache_access", $message);

        $this->render('/json');
    }

}
