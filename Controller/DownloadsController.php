<?php

class DownloadsController extends AppController {

    public function main($country = Null, $file = Null) {

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));

        // Get the requested country
        $requestedCountry = $this->request->params['country'];

        // The download directory
        $downloadsDirectory = dirname(APP) . DS . "Data" . DS . ucfirst($requestedCountry);

        // Get the request file
        $requestedFile = $this->request->params['file'];

        $parts = explode(".", $requestedFile);

        $filename = $parts[0];
        $suffix = $parts[count($requestedFile) - 1];

        $filepath = $downloadsDirectory . DS . $requestedFile;

        if (!file_exists($filepath)) {
            // Log an error attempt
            $message = array(
                'clientIp' => $this->request->clientIp(),
                'method' => $this->request->method(),
                'here' => $this->request->here,
                'referer' => $this->request->referer(),
                'status' => 401,
                'filesize' => 0,
                'text' => "File does not exist: " . $downloadsDirectory . DS . $requestedFile
            );
            CakeLog::write("apache_error", $message);
            throw new NotFoundException("File not found.");
        }

        // Log a successful download
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => filesize($downloadsDirectory . DS . $requestedFile)
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
            'path' => $downloadsDirectory . DS
        );

        $this->set($params);
    }

}

?>
