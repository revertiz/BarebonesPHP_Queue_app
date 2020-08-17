<?php


namespace Code\Model;


class Queue
{
    private $pdo;

    private $time;

    public function __construct(\PDO $pdo, TimeManager $time)
    {
        $this->pdo = $pdo;
        $this->time = $time;
    }

    public function getClients()
    {
        $stmt = $this->pdo->prepare('
    SELECT clients.id, clients.name, surname, service_start, specialists.name as specname, specialists.id as specid,
    (SELECT TIME_TO_SEC(AVG(TIMEDIFF(service_end, service_start))) FROM time WHERE time.specialist_id = specialists.id) as timediffavg
    FROM clients
    LEFT JOIN time ON clients.id = time.client_id
    LEFT JOIN specialists ON time.specialist_id = specialists.id
    WHERE serviced = 0 ORDER BY service_start
    ');

//        $stmt = $this->pdo->prepare('
//    SELECT clients.name, surname, service_start, specialists.name as specname, specialists.id as specid,
//    TIME_TO_SEC(AVG(TIMEDIFF(service_end, service_start))) as timediffavg
//    FROM clients
//    LEFT JOIN time ON clients.id = time.client_id
//    LEFT JOIN specialists ON time.specialist_id = specialists.id
//    WHERE serviced = 0 AND specialists.id = time.specialist_id ORDER BY service_start
//    ');

//        $stmt = $this->pdo->prepare('
//    SELECT TIME_TO_SEC(AVG(TIMEDIFF(time.service_end, time.service_start)))
//    FROM specialists
//    LEFT JOIN time on time.specialist_id = specialists.id
//    WHERE specialists.id = time.specialist_id;
//
//
//    ');

//        $stmt = $this->pdo->prepare('SELECT AVG(TIMEDIFF(service_end, service_start)) as timediffavg FROM time
//');

//        $stmt = $this->pdo->prepare('SELECT SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(service_end, service_start)))) as timediffavg FROM time
//');


        $stmt->execute();

        $result = $stmt->fetchAll();

        return $this->assignWaitTimes($result);


    }

    public function assignWaitTimes(array $array): array
    {
        $specialists = $this->uniqueSpecialists($array);

        foreach ($specialists as $index => $specialistId) {
            $position = 1;

            foreach ($array as $key => $value) {
                if ($value['specid'] == $specialistId) {

                    $array[$key]['wait_time'] = $position * $value['timediffavg'];
                    $position++;
                }
            }
        }
//TODO reikia sortinti ne wait taima o kiek liko tia isimti is registrationview ta logika ir cia perkelti
//var_dump($array);
        usort($array, function ($a, $b) {
            return $a['wait_time'] <=> $b['wait_time'];
        });
//        $this->sortByWaitTimes($array);

        return $array;
    }

    public function uniqueSpecialists(array $array): array
    {
        $specialists = [];
        foreach ($array as $key => $value) {
            $specialists[] = $value['specid'];

        }
        $specialists = array_values(array_unique($specialists));

        return $specialists;
    }

    public function sortByWaitTimes(array $array): array
    {
        usort($array, function ($a, $b) {
            return $a['wait_time'] <=> $b['wait_time'];
        });

        return $array;
    }

//AVG(TIMEDIFF(service_end, service_start)) as timediffavg FROM time WHERE specialist_id = :specialist_id

    public function getAvgWaitTime($specialistId)
    {
        return $this->time->calculateAvgServiceTime($specialistId);
    }

}
