<?php


namespace Code\Model;


class TimeManager
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function setServiceTimeStart($clientId, $specialistId)
    {
        $datetime = date_create()->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare('INSERT INTO time (service_start, specialist_id, client_id) VALUES(:service_start, :specialist_id, :client_id)');
        $stmt->execute([
            'service_start' => $datetime,
            'specialist_id' => $specialistId,
            'client_id' => $clientId
        ]);

    }

    public function setServiceTimeEnd($clientId, $specialistId)
    {
        $datetime = date_create()->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare('UPDATE time SET service_end = :service_end WHERE specialist_id = :specialist_id AND client_id = :client_id');
        $stmt->execute([
            'service_end' => $datetime,
            'specialist_id' => $specialistId,
            'client_id' => $clientId
        ]);

    }

    public function getVisitLength($clientId)
    {
        $stmt = $this->pdo->prepare('SELECT TIMEDIFF(service_end, service_start) AS timediff FROM time WHERE client_id = :client_id');
        $stmt->execute([
            'client_id' => $clientId
        ]);
//        return $stmt->fetchAll();
        $results = $stmt->fetchAll();
        return $results[0]['timediff'];
    }

    public function getWaitTime($clientId)
    {
        return $this->getPosition($clientId);
    }

    public function getPosition($clientId)
    {

        $stmt = $this->pdo->prepare('
WITH NumberedRows AS (SELECT client_id, ROW_NUMBER() OVER (PARTITION BY specialist_id ORDER BY service_start) AS RowNumber
FROM time WHERE service_end IS NULL)
SELECT RowNumber FROM NumberedRows WHERE client_id = :client_id 
');

        $stmt->execute([
            'client_id' => $clientId
        ]);


        $result = $stmt->fetchAll();

        return $result[0]['RowNumber'];
    }


    public function calculateAvgServiceTime($specialistId)
    {

        $stmt = $this->pdo->prepare('SELECT TIME_TO_SEC(AVG(TIMEDIFF(service_end, service_start))) as timediffavg FROM time WHERE specialist_id = :specialist_id');
        $stmt->execute([
            'specialist_id' => $specialistId
        ]);

        $results = $stmt->fetchAll();
//      return as seconds
        $results = $results[0]['timediffavg'];

        return $results;
    }
}
