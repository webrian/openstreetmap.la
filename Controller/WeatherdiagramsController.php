<?php

include_once 'SitesController.php';

class WeatherdiagramsController extends SitesController {

    protected function formatBytes($bytes) {
        if ($bytes < 1024)
            return $bytes . ' B';
        elseif ($bytes < 1048576)
            return round($bytes / 1024, 2) . ' KB';
        else
            return round($bytes / 1048576, 2) . ' MB';
    }

    public function beforeFilter() {
        $lang = $this->_extractLanguage();
        $this->set('lang', $lang);

        $this->Session->write('Config.language', $this->_languages[$lang]);
        $this->_languageCode = $lang;
        Configure::write('Config.language', $this->Session->read('Config.language'));

        // Configure the custom apache like log file
        CakeLog::config('apache.log', array(
                    'engine' => 'CustomFileLog',
                    'path' => dirname(APP) . DS . "app" . DS . "tmp" . DS . "logs" . DS
                ));
    }

    public function index() {
        // Set the host
        $host = $this->request->host();
        $this->set('host', $host);

        // Get the filesize for the database extract
        $file = dirname(APP) . DS . "Data" . DS . "Laos" . DS . "metar_vlvt.bz2";
        $filesize = $this->formatBytes(@filesize($file));
        $this->set('filesize', $filesize);

        // Log a successful search before returning the result
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            // Estimated value
            'filesize' => 1321
        );
        CakeLog::write("apache_access", $message);
    }

    public function dev() {
        // Set the host
        $host = $this->request->host();
        $this->set('host', $host);

        // Get the filesize for the database extract
        $file = dirname(APP) . DS . "Data" . DS . "Laos" . DS . "metar_vlvt.bz2";
        $filesize = $this->formatBytes(@filesize($file));
        $this->set('filesize', $filesize);

        // Log a successful search before returning the result
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            // Estimated value
            'filesize' => 1321
        );
        CakeLog::write("apache_access", $message);
    }

    public function temperature() {

        $this->response->type('application/json');

        $start = '20120101';
        if (isset($this->request->query['startdate'])) {
            $start = $this->request->query['startdate'];
        }
        $end = '20120201';
        if (isset($this->request->query['enddate'])) {
            $end = $this->request->query['enddate'];
        }

        $startDate = strtotime($start);
        $sqlStartDate = "DATE('" . date('Y-m-d', $startDate) . "')";

        $endDate = strtotime($end);
        $sqlEndDate = "DATE('" . date('Y-m-d', $endDate) . "')";

        $timespan = "Weatherdiagram.time >= $sqlStartDate AND Weatherdiagram.time <= $sqlEndDate";

        $minTempResult = $this->Weatherdiagram->find('all', array(
                    'fields' => array('MIN(Weatherdiagram.temperature_celsius) AS min,
                        MAX(Weatherdiagram.temperature_celsius) AS max,
                        AVG(Weatherdiagram.temperature_celsius) AS average,
                        DATE("Weatherdiagram"."time" AT TIME ZONE \'ICT\') AS day'),
                    'group' => 'day',
                    'order' => 'day ASC',
                    'conditions' => array($timespan)
                ));

        $countTempResult = count($minTempResult);

        // If the time span is more than half a year, show only one value per week
        if ($countTempResult > 178) {

            $minTempResult = $this->Weatherdiagram->find('all', array(
                        'fields' => array('MIN(Weatherdiagram.temperature_celsius) AS min,
                        MAX(Weatherdiagram.temperature_celsius) AS max,
                        AVG(Weatherdiagram.temperature_celsius) AS average,
                        TO_CHAR("Weatherdiagram"."time", \'YYYY-WW\') AS day'),
                        'group' => 'day',
                        'order' => 'day ASC',
                        'conditions' => array($timespan)
                    ));
        }

        //echo var_dump($result[0]);

        $data = array();

        for ($i = 0; $i < count($minTempResult); $i++) {
            $minMaxRow = $minTempResult[$i][0];
            array_push($data, array(
                't' => $minMaxRow['average'],
                'min' => $minMaxRow['min'],
                'max' => $minMaxRow['max'],
                'day' => $minMaxRow['day'])
            );
        }

        $this->set('content', array('success' => true, 'total' => count($minTempResult), 'data' => $data));

        // Log a successful place search before returning the result
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => strlen(json_encode($data))
        );
        CakeLog::write("apache_access", $message);
    }

    public function day() {

        $this->response->type('application/json');

        $day = Null;
        if (isset($this->request->query['date'])) {
            $day = $this->request->query['date'];
        }

        $date = strtotime($day);
        $sqlDate = "DATE('" . date('Y-m-d', $date) . "' AT TIME ZONE 'ICT')";

        if ($date != Null) {
            $conditions = "DATE(\"time\" AT TIME ZONE 'ICT') = $sqlDate";
        } else {
            $conditions = "DATE(\"time\" AT TIME ZONE 'ICT') = DATE((SELECT now() AT TIME ZONE 'ICT'))";
        }

        $currentResult = $this->Weatherdiagram->find('all', array(
                    'fields' => array(
                        "TIMETZ(\"Weatherdiagram\".\"time\" AT TIME ZONE 'ICT') AS time,
                        DATE(\"Weatherdiagram\".\"time\" AT TIME ZONE 'ICT') AS date,
                        temperature_celsius,
                        dew_point_celsius,
                        humidity,
                        pressure,
                        wind_speed,
                        wind_direction,
                        wind_compass,
                        visibility_kilometers,
                        weather,
                        raw_metar_code,
                        gid"
                    ),
                    'order' => '"Weatherdiagram"."time" ASC',
                    'conditions' => array($conditions)
                ));

        //$log = $this->Weatherdiagram->getDataSource()->getLog(false, false);
        //echo var_dump($currentResult);

        $data = array();

        for ($i = 0; $i < count($currentResult); $i++) {
            $row = $currentResult[$i][0];
            array_push($data, array(
                'gid' => $row['gid'],
                'time' => $row['time'],
                'date' => $row['date'],
                'temperature_celsius' => (float) $row['temperature_celsius'],
                'dew_point_celsius' => (float) $row['dew_point_celsius'],
                'humidity' => (float) $row['humidity'],
                'pressure' => (float) $row['pressure'],
                'wind_speed' => (float) $row['wind_speed'],
                'wind_direction' => (float) $row['wind_direction'],
                'wind_compass' => $row['wind_compass'],
                'visibility_kilometers' => (float) $row['visibility_kilometers'])
            );
        }

        $this->set('content', array('success' => true, 'data' => $data));

        // Log a successful place search before returning the result
        $message = array(
            'clientIp' => $this->request->clientIp(),
            'method' => $this->request->method(),
            'here' => $this->request->here,
            'referer' => $this->request->referer(),
            'status' => 200,
            'filesize' => strlen(json_encode($data))
        );
        CakeLog::write("apache_access", $message);
    }

}

?>
