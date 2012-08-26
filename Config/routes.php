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
Router::connect('/main.js', array('controller' => 'scripts', 'action' => 'main'));
Router::connect('/search', array('controller' => 'locations', 'action' => 'index'));
Router::connect('/route', array('controller' => 'routes', 'action' => 'index'));
Router::connect('/downloads/:country/:file',
                array('controller' => 'downloads', 'action' => 'main'),
                array('file' => '[0-9a-zA-Z\.\-\_]+', 'country' => '[a-zA-Z]+')
);
Router::connect('/files/:country/:file',
                array('controller' => 'downloads', 'action' => 'main'),
                array('file' => '[a-zA-Z0-9\.\-\_]+')
);
Router::connect('/map',
                array('controller' => 'sites', 'action' => 'main')
);
Router::connect('/downloads',
                array('controller' => 'sites', 'action' => 'main', 'downloads')
);
Router::connect('/edit',
                array('controller' => 'sites', 'action' => 'main', 'edit')
);
Router::connect('/about',
                array('controller' => 'sites', 'action' => 'main', 'about')
);
Router::connect('/', array('controller' => 'sites', 'action' => 'main'));


/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
//CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
//require CAKE . 'Config' . DS . 'routes.php';
