<?php

include 'geohash.class.php';

class LocationsController extends AppController {

    public function index(){
        $this->response->type('json');
        
        $result = array();
        
        if(isset($this->request->query['q'])) {
            // Get the search string
            $search = pg_escape_string($this->request->query['q']);
            
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
        
            foreach($data as $tuple) {
            
                $row = $tuple['Location'];
                $hash = $geohash->encode($row['lat'], $row['lon']);
                array_push($result['data'], array('name' => $row['name'], 'hash' => $hash, 'feature' => $row['feature']));
            }
        }
        
        $this->set('data', $result);
        
        $this->render('/json');
    }

}
