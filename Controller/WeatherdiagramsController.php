<?php

class WeatherdiagramsController extends AppController {

    public function index() {
        $host = $this->request->host();
        $this->set('host', $host);
    }

    public function dev() {
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
    }

    public function day() {

        $this->response->type('application/json');

        $day = Null;
        if (isset($this->request->query['date'])) {
            $day = $this->request->query['date'];
        }

        $date = strtotime($day);
        $sqlDate = "DATE('" . date('Y-m-d', $date) . "')";

        if ($date != Null) {
            $conditions = "DATE(\"time\") = $sqlDate";
        } else {
            $conditions = "DATE(\"time\") = DATE((SELECT now()))";
        }

        $currentResult = $this->Weatherdiagram->find('all', array(
                    'fields' => array(
                        "TIMETZ(\"Weatherdiagram\".\"time\") AS time,
                        DATE(\"Weatherdiagram\".\"time\") AS date,
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
    }

}

?>
