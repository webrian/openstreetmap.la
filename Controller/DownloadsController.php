<?php

class DownloadsController extends AppController {

    public function main($file = Null) {

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));

        // The download directory
        $downloadsDirectory = dirname(APP) . DS . "public_html" . DS . "files1" . DS;

        // Get the request file
        $requestedFile = $this->request->params['file'];

        $parts = explode(".", $requestedFile);

        $filename = $parts[0];
        $suffix = $parts[count($requestedFile) - 1];

        $filepath = $downloadsDirectory . $requestedFile;

        if (!file_exists($filepath)) {
            // Log an error attempt
            $message = array(
                'clientIp' => $this->request->clientIp(),
                'method' => $this->request->method(),
                'here' => $this->request->here,
                'referer' => $this->request->referer(),
                'status' => 401,
                'filesize' => 0,
                'text' => "File does not exist: $downloadsDirectory$requestedFile"
            );
            CakeLog::write("apache_error", $message);
            throw new NotFoundException();
        }

        // Log a successful download
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => filesize($downloadsDirectory . $requestedFile)
        );
        CakeLog::write("apache_access", $message);

        $this->viewClass = 'Media';

        // Return app/files/file
        $params = array(
            'id' => $requestedFile,
            'name' => $requestedFile,
            'extension' => $suffix,
            'download' => true,
            'mimeType' => array(
                $suffix => 'application/octet-stream'
            ),
            'path' => $downloadsDirectory
        );

        $this->set($params);
    }

}

?>
