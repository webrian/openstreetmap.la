<?php

/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
Router::connect('/downloads/:country/:file',
                array('controller' => 'downloads', 'action' => 'main'),
                array('country' => '[a-zA-Z]+', 'file' => '[0-9a-zA-Z\.\-\_]+')
);
Router::connect('/files/:country/:file',
                array('controller' => 'downloads', 'action' => 'main'),
                array('country' => '[a-zA-Z]+', 'file' => '[a-zA-Z0-9\.\-\_]+')
);
Router::connect('/map',
                array('controller' => 'sites', 'action' => 'main')
);
Router::connect('/downloads',
                array('controller' => 'sites', 'action' => 'main', 'downloads')
);
// Just to preserve already published URL
Router::connect('/files/cambodia',
                array('controller' => 'sites', 'action' => 'main', 'downloads')
);
Router::connect('/edit',
                array('controller' => 'sites', 'action' => 'main', 'edit')
);
Router::connect('/about',
                array('controller' => 'sites', 'action' => 'main', 'about')
);
Router::connect('/', array('controller' => 'sites', 'action' => 'main'));

// Define the route to the OpenSearch description
Router::connect('/places.xml', array('controller' => 'places', 'action' => 'opensearchdescription'));

// Define the routes for the Tile Map Service
Router::connect('/tms/1.0.0',
               array('controller' => 'tms', 'action' => 'tilemapservice')
);
Router::connect('/tms/1.0.0/services/tilemapservice.xml',
               array('controller' => 'tms', 'action' => 'tilemapservice')
);
Router::connect('/tms/1.0.0/:layer',
               array('controller' => 'tms', 'action' => 'tilemap')
);
Router::connect('/tms/1.0.0/:layer/:zoom/:column/:row.:format',
               array('controller' => 'tms', 'action' => 'tiles')
);
Router::connect('/landsat8',
               array('controller' => 'sites', 'action' => 'landsat8')
);
Router::connect('/topomap',
               array('controller' => 'sites', 'action' => 'topomap')
);


/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
//CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
