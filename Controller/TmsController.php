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
    private $tilesdir = "";
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
        $this->tilesdir = ROOT . DS . "Data" . DS . "tiles";
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
        $this->tilesdir = ROOT . DS . "Data" . DS . "tiles";

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
        $resourceFile = $this->tilesdir .DS . $layer . DS . "tilemapresource.xml";
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
        $this->tilesdir = ROOT . DS . "Data" . DS . "tiles";

        $layer = $this->request->params['layer'];
        $zoom = $this->request->params['zoom'];
        $column = $this->request->params['column'];
        $row = $this->request->params['row'];
        $format = $this->request->params['format'];

        $this->autoRender = false;
        switch($format) {
            case "jpeg":
                $this->response->type("image/jpeg");
                break;
            case "jpg":
                $this->response->type("image/jpeg");
            default:
                $this->response->type('image/png');
        }

        try {
            // Open the database: Check first if there is a separate mbtiles
            // database for the requested zoom level
            if(file_exists($this->tilesdir . "/$layer/tiles.$zoom.mbtiles")){
                 $dbpath = "sqlite:" . $this->tilesdir. "/$layer/tiles.$zoom.mbtiles";
            } else {
                 $dbpath = "sqlite:$this->tilesdir/$layer/tiles.mbtiles";
            }
            $conn = new PDO($dbpath);

            // Query the tiles view and echo out the returned image
            $sql = "SELECT * FROM tiles WHERE zoom_level = $zoom AND tile_column = $column AND tile_row = $row";
            $q = $conn->prepare($sql);
            $q->execute();

            $q->bindColumn(1, $zoom_level);
            $q->bindColumn(2, $tile_column);
            $q->bindColumn(3, $tile_row);
            $q->bindColumn(4, $tile_data, PDO::PARAM_LOB);

            $result = $q->fetchAll();
            if (count($result) == 0) {
                $emptyFile = $this->tilesdir . DS . "empty.png";
                $this->response->body(file_get_contents($emptyFile));
            } else {
                $this->response->body($result[0]['tile_data']);
            }

        } catch (Exception $e) {
            print 'Exception : ' . $e->getMessage();
        }

    }

}

?>
