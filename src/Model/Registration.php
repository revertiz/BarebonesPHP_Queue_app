<?php


namespace Code\Model;


use Cassandra\Time;

class Registration
{

    private $pdo;

    private $record;

    private $errors;

    private $submitted;

    private $timeManager;

    private $token;

    public function __construct(\PDO $pdo, TimeManager $timeManager, Token $token, array $record = [], $submitted = false, array $errors = [])
    {
        $this->pdo = $pdo;
        $this->record = $record;
        $this->errors = $errors;
        $this->submitted = $submitted;
        $this->timeManager = $timeManager;
        $this->token = $token;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getRecord(): array
    {
        return $this->record;
    }

    public function save(array $record)
    {
        return $this->insert($record);
    }

    public function insert(array $record): Registration
    {

        //validate record for errors and return new object with errors if there are any
        $errors = $this->validate($record);
        if (!empty($errors)) {
            return new Registration($this->pdo, $this->timeManager, $this->token, $record, false, $errors);
        }

        //insert client into database
        $stmt = $this->pdo->prepare('INSERT INTO clients (name,surname) VALUES(:name,:surname)');
        $stmt->execute(['name' => $record['name'], 'surname' => $record['surname']]);

        //set $record['id']
        $record['id'] = $this->pdo->lastInsertId();

        //create and set unique token using client id
        $record['token'] = $this->token->generateId($record['id']);
        $stmt = $this->pdo->prepare('UPDATE clients SET token = :token WHERE id = :id');
        $stmt->execute(['token' => $record['token'], 'id' => $record['id']]);

        //insert registration time to database
        $this->timeManager->setServiceTimeStart($record['id'], $record['specialist_id']);

        //return new object with $record and $submitted set to true
        return new Registration($this->pdo, $this->timeManager, $this->token, $record, true);
    }

    public function validate(array $record): array
    {
        $errors = [];

        if (empty($record['name'])) {
            $errors[] = 'Irasykite varda';
        }
        if (empty($record['surname'])) {
            $errors[] = 'Irasykite pavarde';
        }
        if (!is_numeric(($record['specialist_id']))) {
            $errors[] = 'Pasirinkite specialista';
        }

        return $errors;
    }

    public function isSubmitted(): bool
    {
        return $this->submitted;
    }
}
