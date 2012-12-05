<?php

App::uses('Sanitize', 'Utility');

include 'geohash.class.php';

class PlacesController extends AppController {

    private $itemsPerPage = 15;

    public function beforeFilter() {
        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));
    }

    /**
     * Returns an OpenSearch compatible description file. See also the OpenSearch specifications:
     * http://www.opensearch.org/Specifications/OpenSearch/1.1#OpenSearch_description_document
     */
    public function opensearchdescription() {
        // Set the correct content type
        $this->response->type('application/opensearchdescription+xml');
        // Set the host to the output
        $host = $this->request->host();
        $this->set('host', $host);
    }

    public function index() {
        // Set the content type to json
        $this->response->type('application/json');
        $result = $this->searchPlace();
        $this->set('result', $result);
    }

    public function rss() {
        // Set the correct content type
        $this->response->type('application/rss+xml');
        $result = $this->searchPlace();
        $this->set('result', $result);
    }

    /**
     * Returns search suggestions that can be used in browsers.
     */
    public function suggest() {
        // Set the content type to json
        $this->response->type('application/json');
        $result = $this->searchPlace();
        $this->set('result', $result);
    }

    /**
     * Does the actual database query. Returns a result array with an array of
     * matching items and metadata.
     * The result looks like:
     * result: {
     *   'items':[
     *   {'name': name, 'lat': lat, 'lon': lon},
     *   {'name': name, 'lat': lat, 'lon': lon},
     *   ],
     *   'metadata':{
     *   'success': true,
     *   'totalResults': 100,
     *   etc.
     *   }
     * }
     * @return array
     */
    private function searchPlace() {

        $result = array();

        // An array that holds the matched records
        $items = array();
        // An associative array with metadata
        $metadata = array();

        $search = "";
        if (isset($this->request->query['q'])) {
            // Get the search string and clean it
            //$search = Sanitize::clean($this->request->query['q']);
            $search = Sanitize::escape($this->request->query['q']);
        }

        // Create the query condition. Use POSIX regular expressions in PostgreSQL,
        // see also: http://www.postgresql.org/docs/9.1/static/functions-matching.html
        $conditions = array("Place.name ~* '$search'");

        // Get the requested page, default is the first page
        $page = 1;
        if (isset($this->request->query['p']) && $this->request->query['p'] > 0) {
            $page = $this->request->query['p'];
        }

        // Calculate the offset
        $offset = ($page - 1) * $this->itemsPerPage;

        // Query the database
        $records = $this->Place->find('all', array(
                    'conditions' => $conditions,
                    'offset' => $offset,
                    'limit' => $this->itemsPerPage,
                    'order' => "Place.name"
                ));

        // Count the number of results
        $totalResults = $this->Place->find('count', array(
                    'conditions' => $conditions
                ));


        // Log SQL queries
        //$log = $this->Place->getDataSource()->getLog(false, false);
        //echo var_dump($log);

        // Use Geohash to encode the coordinates for the JSON output
        $geohash = new Geohash();

        // Loop all records and create an associative array per matching feature
        foreach ($records as $record) {
            $row = $record['Place'];
            $hash = $geohash->encode($row['lat'], $row['lon']);
            array_push($items, array(
                'name' => $row['name'],
                'hash' => $hash,
                'feature' => $row['feature'],
                'lat' => $row['lat'],
                'lon' => $row['lon'])
            );
        }

        // Write the metadata
        $metadata['totalResults'] = $totalResults;
        $metadata['startIndex'] = $offset;
        $metadata['itemsPerPage'] = count($records);
        $metadata['startPage'] = $page;
        $metadata['success'] = true;
        $metadata['searchTerm'] = $search;
        $metadata['host'] = $this->request->host();

        $result['data'] = $items;
        $result['metadata'] = $metadata;

        // Log a successful place search before returning the result
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => strlen(json_encode($result))
        );
        CakeLog::write("apache_access", $message);

        return $result;
    }

}