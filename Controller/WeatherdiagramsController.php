<?php

class WeatherdiagramsController extends AppController {

    public function index() {
        $host = $this->request->host();
        $this->set('host', $host);
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
                        DATE(Weatherdiagram.time) AS day'),
                    'group' => 'day',
                    'order' => 'day ASC',
                    'conditions' => array($timespan)
                ));

        $countTempResult = count($minTempResult);

        if ($countTempResult > 300) {

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

        $this->set('content', array('success' => true, 'data' => $data));
    }

    public function current() {

        $this->response->type('application/json');

        $conditions = "\"time\" >= TIMESTAMPTZ('2012-12-05 00:01:00+01') - interval '2 day'";

        $currentResult = $this->Weatherdiagram->find('all', array(
                    'order' => '"Weatherdiagram"."time" ASC',
                    'conditions' => array($conditions)
                ));

        $data = array();

        for ($i = 0; $i < count($currentResult); $i++) {
            $row = $currentResult[$i]['Weatherdiagram'];
            array_push($data, array(
                'gid' => $row['gid'],
                'time' => $row['time'],
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
    }

}

?>
