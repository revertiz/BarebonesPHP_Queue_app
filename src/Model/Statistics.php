<?php


namespace Code\Model;



class Statistics
{
    private $time;
    private $pdo;
    private $weekday;
    private $specialist;

    public function __construct(\PDO $pdo, TimeManager $time, $weekday = [], $specialist = [])
    {
        $this->pdo = $pdo;
        $this->time = $time;
        $this->weekday = $weekday;
        $this->specialist = $specialist;
    }

    public function specialist($specialist): self
    {
        return new self($this->pdo, $this->time, $this->weekday, $specialist);
    }

    public function weekday($weekday): self
    {
        return new self($this->pdo, $this->time, $weekday, $this->specialist);
    }

    public function getSpecialists()
    {
        return $this->specialist;
    }

    public function getWeekdays()
    {
        return $this->weekday;
    }

    public function getStats()
    {


//        print_r($this->specialist);
//        print_r($this->weekday);
        if (empty($this->specialist)){
            $specialists = '';
        } else {
            $specialists = implode(',', $this->specialist);
        }

        if (empty($this->weekday)){
            $weekdays = '';
        } else {
            $weekdays = implode (',', $this->weekday);
        }
//        var_dump($specialists);
//        print_r($weekdays);

//        var_dump($specialistArray);
//        $stmt = $this->pdo->prepare('SELECT HOUR(service_start) as hour, COUNT(*) as hour_counts FROM time WHERE WEEKDAY(service_start) = 0 GROUP BY hour');
//        $stmt = $this->pdo->prepare('SELECT WEEKDAY(service_start) as weekday, specialist_id FROM time GROUP BY weekday,specialist_id');

        $stmt = $this->pdo->prepare('SELECT hour, hour_counts, weekday, AllTable.specialist_id FROM
        (SELECT specialist_id, HOUR(service_start) as hour, WEEKDAY(service_start) as weekday FROM time WHERE specialist_id = 1) AS AllTable');

        $stmt = $this->pdo->prepare('SELECT hour, hour_counts, weekday FROM 
        (SELECT HOUR(service_start) as hour,COUNT(*) as hour_counts, WEEKDAY(service_start) as weekday FROM time
        WHERE specialist_id IN (' . $specialists . ') GROUP BY hour, weekday) AS GroupedTabled
        WHERE weekday IN ('. $weekdays .') GROUP BY weekday, hour ORDER BY weekday');



//        $sql = 'SELECT *
//          FROM `table`
//         WHERE `id` IN (' . implode(',', array_map('intval', $array)) . ')';

        $stmt->execute();
        $results = $stmt->fetchAll();
//        print_r($results);
        return $results;
    }
}
