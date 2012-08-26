<?php

/**
 * File Storage stream for Logging
 *
 * PHP 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       Cake.Log.Engine
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('CakeLogInterface', 'Log');

/**
 * File Storage stream for Logging.  Writes logs to different files
 * based on the type of log it is.
 *
 * @package       Cake.Log.Engine
 */
class CustomFileLog implements CakeLogInterface {

    /**
     * Path to save log files on.
     *
     * @var string
     */
    protected $_path = null;

    /**
     * Constructs a new File Logger.
     *
     * Options
     *
     * - `path` the path to save logs on.
     *
     * @param array $options Options for the FileLog, see above.
     */
    public function __construct($options = array()) {
        $options += array('path' => LOGS);
        $this->_path = $options['path'];
    }

    /**
     * Implements writing to log files.
     *
     * @param string $type The type of log you are making.
     * @param array $message The message you want to log.
     * @return boolean success of write.
     */
    public function write($type, $message) {
        $debugTypes = array('notice', 'info', 'debug');

        if ($type == 'error' || $type == 'warning') {
            $filename = $this->_path . 'error.log';
        } elseif ($type == 'apache_error') {
            $filename = $this->_path . $type . '.log';
            // Write an Apache like error message
            $clientIp = $message['clientIp'];
            $text = $message['text'];
            $output = "[" . date('D M d H:m:s Y') . "] [error] [client $cientIp] $text\n";
        } elseif ($type == 'apache_access') {
            $filename = $this->_path . $type . '.log';
            // Get the necessary information to log correctly in Apache style
            $clientIp = $message['clientIp'];
            $method = $message['method'];
            $here = $message['here'];
            $status = $message['status'];
            $filesize = $message['filesize'];
            $referer = $message['referer'];
            $output = "$clientIp - - [" . date('d/M/Y:H:m:s O') . "] \"$method "
                    . "$here\" $status $filesize \"$referer\" "
                    . "\"" . $_SERVER['HTTP_USER_AGENT'] . "\"\n";
        } elseif (in_array($type, $debugTypes)) {
            $filename = $this->_path . 'debug.log';
        } else {
            $filename = $this->_path . $type . '.log';
            $output = $message;
        }

        return file_put_contents($filename, $output, FILE_APPEND);
    }

}
