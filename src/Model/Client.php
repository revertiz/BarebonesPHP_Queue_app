<?php


namespace Code\Model;


class Client
{
    private $pdo;
    private $time;
    private $queue;
    private $id;
    private $name;
    private $surname;
    private $serviced;
    private $waitTime;
    private $visitLength;
    private $specialistName;
    private $specialistId;
    private $serviceStart;


    public function __construct(\PDO $pdo, TimeManager $time, Queue $queue, $results = [], $waitTime = null, $visitLength = null)
    {
        $this->pdo = $pdo;
        $this->time = $time;
        $this->queue = $queue;
        $this->id = $results['id'];
        $this->name = $results['name'];
        $this->surname = $results['surname'];
        $this->serviced = $results['serviced'];
        $this->waitTime = $waitTime;
        $this->visitLength = $visitLength;
        $this->specialistName = $results['specialist_name'];
        $this->specialistId = $results['specialist_id'];
        $this->serviceStart = $results['service_start'];

    }
    public function getClientById($clientId)
    {
        $stmt = $this->pdo->prepare('SELECT clients.id, clients.name, surname, serviced, service_start, specialists.name as specialist_name, specialists.id as specialist_id FROM clients LEFT JOIN time ON clients.id = time.client_id LEFT JOIN specialists ON time.specialist_id = specialists.id WHERE clients.id = :client_id');
        $stmt->execute(['client_id' => $clientId]);
        $results = $stmt->fetchAll();
        $results = $results[0];
//        var_dump($results);
//        return $stmt->fetchAll();
        return new Client($this->pdo, $this->time, $this->queue, $results);
    }

    public function getClientByToken($token)
    {
        $stmt = $this->pdo->prepare('SELECT clients.id, clients.name, surname, serviced, service_start, specialists.name as specialist_name, specialists.id as specialist_id FROM clients LEFT JOIN time ON clients.id = time.client_id LEFT JOIN specialists ON time.specialist_id = specialists.id WHERE clients.token = :token');
        $stmt->execute(['token' => $token]);
        $results = $stmt->fetchAll();
        $results = $results[0];

//        return $stmt->fetchAll();
        return new Client($this->pdo, $this->time, $this->queue, $results);
    }

    public function deleteClient($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM clients WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $this;
    }

    public function isLast()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM time  LEFT JOIN clients ON time.client_id = clients.id WHERE specialist_id = :specialist_id ORDER BY service_start DESC LIMIT 1');
        $stmt->execute(['specialist_id' => $this->specialistId]);
        $results = $stmt->fetchAll();
        if($this->id == $results[0]['client_id'])
        {
            return true;
        }
        return false;
    }

    public function delay($specialistId, $clientId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM time  LEFT JOIN clients ON time.client_id = clients.id WHERE specialist_id = :specialist_id AND serviced = 0 ORDER BY service_start');
        $stmt->execute(['specialist_id' => $specialistId]);
        $results = $stmt->fetchAll();
        $index =0;
        foreach ($results as $key => $value){

            if ($value['id'] == $clientId){
                $clientsId =  $results[$index]['id'];
                $clientsServiceStart = $results[$index]['service_start'];
                $index = $index +1;
                break;
            }
            $index++;
        }

        $nextClientsId = $results[$index]['id'];
        $nextClientsServiceStart = $results[$index]['service_start'];

        $stmt = $this->pdo->prepare('UPDATE time SET service_start = :service_start WHERE client_id = :client_id');
        $stmt->execute(['service_start' => $nextClientsServiceStart, 'client_id' => $clientsId]);
        $stmt->execute(['service_start' => $clientsServiceStart, 'client_id' => $nextClientsId]);

        return $this;
//        return $this->getClientById($this->id);

    }

    public function getWaitTime()
    {
        return gmdate("H:i:s",$this->time->calculateAvgServiceTime($this->specialistId) * $this->getPosition());
    }

    public function getTimeLeft()
    {
        return gmdate("H:i:s",$this->time->calculateAvgServiceTime($this->specialistId) * $this->getPosition() + strtotime($this->serviceStart) - strtotime(gmdate("H:i:s")));
    }

    public function getAvgServiceTime()
    {
        return $this->time->calculateAvgServiceTime($this->specialistId);
    }

    public function getVisitLength()
    {
        return $this->time->getVisitLength($this->id);
    }

    public function getPosition()
    {
        return $this->time->getPosition($this->id);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getServiced()
    {
        return $this->serviced;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSpecialistId()
    {
        return $this->specialistId;
    }

}
