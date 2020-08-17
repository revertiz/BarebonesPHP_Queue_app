<?php


namespace Code\Model;


class Specialist
{
    private $pdo;

    private $timeManager;

    public function __construct(\PDO $pdo, TimeManager $timeManager)
    {
        $this->pdo = $pdo;
        $this->timeManager = $timeManager;
    }

    public function serviceClient($id, $specialistId) : self
    {
        $stmt = $this->pdo->prepare('UPDATE clients SET serviced = 1 WHERE id=:id');
        $stmt->execute(['id' => $id]);
        $this->timeManager->setServiceTimeEnd($id, $specialistId);
        return $this;
    }

    public function getClients($specialistId)
    {
//        $stmt = $this->pdo->prepare('SELECT serviced,clients.id,name,surname,queue.position FROM clients LEFT JOIN queue ON clients.id = queue.person_id WHERE specialist_id = :specialist_id');
        $stmt = $this->pdo->prepare('SELECT clients.id, name, surname FROM clients LEFT JOIN time ON clients.id = time.client_id WHERE specialist_id = :specialist_id AND serviced = 0 ORDER BY service_start');
        $stmt->execute(['specialist_id' => $specialistId]);
        return $stmt->fetchAll();
    }

    public function getSpecialists()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM specialists');
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
