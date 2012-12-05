<?php

/**
 * A Tile Map Service that follows the OSGeo proposal at
 * http://wiki.osgeo.org/wiki/Tile_Map_Service_Specification
 */
class TmsController extends AppController {

    /**
     * Local file path to the tiles directory
     * @var String
     */
    private $tilesdir = "/home/openstreet/Data/tiles";
    private $rootService = "tms";
    private $tileMapService = "1.0.0";

    /**
     * Handles the Root resource according to the OSGeo specification
     * http://wiki.osgeo.org/wiki/Tile_Map_Service_Specification#Root_Resource
     */
    public function index() {
        // Set the output type to text/xml
        $this->response->type('text/xml');
        // The url to the TileMapService resource
        $url = "http://" . $this->request->host() . DS . $this->rootService . DS . $this->tileMapService . DS;
        $this->set('url', $url);
    }

    /**
     * Returns the TileMapService resource according to the OSGeo specification
     * http://wiki.osgeo.org/wiki/Tile_Map_Service_Specification#TileMapService_Resource
     */
    public function tilemapservice() {
        // Set the content type to text/xml
        $this->response->type('text/xml');

        // List of all TileMaps
        $tileMaps = array();

        // Open the tiles directory and loop all layers assuming that one
        // subdirectory represents one layer.
        if ($handle = opendir($this->tilesdir)) {
            while (false !== ($layer = readdir($handle))) {
                // Exclude the current and parent directory and check if the
                // directory is not a file.
                if ($layer != "." && $layer != ".." && is_dir($this->tilesdir . DS . $layer)) {

                    // Get the tilemapresource.xml file from the layer
                    $resourceFile = "$this->tilesdir/$layer/tilemapresource.xml";
                    // Check if the file exists and is readable
                    if (!is_readable($resourceFile)) {
                        throw new NotFoundException("Layer metadata not found.");
                    }

                    // Open the xml file
                    $doc = new DOMDocument();
                    $doc->load($resourceFile);

                    // Construct the layer url
                    //$href = "http://" . $this->request->host() . "/tms/1.0.0/" . $layer . "/";
                    $href = "http://" . $this->request->host() . DS . $this->rootService
                            . DS . $this->tileMapService . DS . $layer . DS;
                    // Extract the profile from the file
                    $tileSetsNode = $doc->getElementsByTagName('TileSets')->item(0);
                    $profile = $tileSetsNode->getAttribute('profile');
                    // Extract the title from the file
                    $titleNode = $doc->getElementsByTagName('Title')->item(0);
                    $title = $titleNode->firstChild->textContent;
                    // Finally extract the spatial reference system
                    $srsNode = $doc->getElementsByTagName('SRS')->item(0);
                    $srs = $srsNode->firstChild->textContent;

                    // Add the current layer to the list of TileMaps
                    array_push($tileMaps, array(
                        'href' => $href,
                        'title' => $title,
                        'srs' => $srs,
                        'profile' => $profile));
                }
            }
            // Close the directory handle
            closedir($handle);
        }

        // Set the list of TileMaps to the output
        $this->set('tileMaps', $tileMaps);
        // Set the url to the Root resource to the output
        $rootServices = "http://" . $this->request->host() . DS . $this->rootService;
        $this->set('rootServices', $rootServices);
    }

    /**
     * Returns a TileMap resource for a layer according to the OSGeo specification
     * http://wiki.osgeo.org/wiki/Tile_Map_Service_Specification#TileMap_Resource
     */
    public function tilemap() {

        // Set the Content-Type to text/xml
        $this->response->type('text/xml');
        // Do not autoRender
        //$this->autoRender = false;
        // Get the layer parameter
        if (isset($this->request->params['layer'])) {
            $layer = $this->request->params['layer'];
        } else {
            throw new NotFoundException("Missing layer parameter.");
        }

        // Url to the TileMapService resource
        $tileMapService = "http://" . $this->request->host() . DS . $this->rootService
                . DS . $this->tileMapService . DS;

        // Get the tilemapresource.xml file for the layer
        $resourceFile = "$this->tilesdir/$layer/tilemapresource.xml";
        if (!is_readable($resourceFile)) {
            throw new NotFoundException("Layer metadata not found.");
        }

        // Open the xml file
        $doc = new DOMDocument();
        $doc->load($resourceFile);

        $root = $doc->getElementsByTagName('TileMap')->item(0);
        $root->setAttribute("tilemapservice", $tileMapService);

        // Loop all TileSets
        $tileSetsNodeList = $root->getElementsByTagName('TileSets');
        $tileSetNodeList = $tileSetsNodeList->item(0)->getElementsByTagName('TileSet');
        for ($i = 0; $i < $tileSetNodeList->length; $i++) {
            $tileSet = $tileSetNodeList->item($i);
            $zoomLevel = $tileSet->getAttribute('order');
            $zoomUrl = $tileMapService . "$layer/$zoomLevel";
            $tileSet->setAttribute("href", $zoomUrl);
        }

        $this->set('content', $doc);
    }

    public function tiles() {
        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));


        $this->autoRender = false;
        $this->response->type('image/png');

        $layer = $this->request->params['layer'];
        $zoom = $this->request->params['zoom'];
        $x = $this->request->params['column'];
        $y_tms = $this->request->params['row'];

        // Flip the y coordinate from Google compatible to OGC TMS standard
        $y = pow(2, $zoom) - 1 - $y_tms;

        $file = $this->tilesdir . DS . $layer . DS . $zoom . DS . $x . DS . $y . ".png";
        //echo $file;

        if (is_readable($file)) {

            // Log a successful tile delivery
            $message = array(
                'clientIp' => $this->request->clientIp(),
                'method' => $this->request->method(),
                'here' => DS . $this->rootService . DS . $this->tileMapService . DS . $layer,
                'referer' => $this->request->referer(),
                'status' => 200,
                'filesize' => filesize($file)
            );
            CakeLog::write("apache_access", $message);

            $this->response->body(file_get_contents($file));
        } else {
            $emptyFile = $this->tilesdir . DS . "empty.png";
            // Log a successful tile delivery
            $message = array(
                'clientIp' => $this->request->clientIp(),
                'method' => $this->request->method(),
                'here' => DS . $this->rootService . DS . $this->tileMapService . DS . $layer,
                'referer' => $this->request->referer(),
                'status' => 200,
                'filesize' => filesize($emptyFile)
            );
            CakeLog::write("apache_access", $message);
            $this->response->body(file_get_contents($emptyFile));
        }
    }

}

?>
