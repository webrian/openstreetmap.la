<?php

class RedirectController extends AppController {

    public function beforeFilter() {
        $this->autoRender = false;
    }

    public function index() {

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));

        if (isset($this->request->query['u'])) {

            $externalUrl = $this->request->query['u'];

            $externalDomain = explode('?', $externalUrl);

            // Log a successful place search
            $message = array(
                'clientIp' => $this->request->clientIp(),
                'method' => $this->request->method(),
                'here' => $this->request->here . "?u=" . $externalDomain[0],
                'referer' => $this->request->referer(),
                'status' => 302,
                'filesize' => 0
            );
            CakeLog::write("apache_access", $message);

            $this->redirect($externalUrl, 404, true);
        } else {
            $this->redirect(array('controller' => 'sites', 'action' => 'main'));
        }
    }

}
