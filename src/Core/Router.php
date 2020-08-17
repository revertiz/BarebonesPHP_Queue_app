<?php


namespace Code\Core;


class Router
{
    private $table = array();

    public function __construct()
    {
        $this->table['default'] = new Route('Code\Model\Registration', 'Code\Registration\RegistrationView', 'Code\Registration\RegistrationController');
        $this->table['register'] = new Route('Code\Model\Registration', 'Code\Registration\RegistrationView', 'Code\Registration\RegistrationController');
        $this->table['queue'] = new Route('Code\Model\Queue', 'Code\Queue\QueueView', 'Code\Queue\QueueController');
        $this->table['specialist'] = new Route('Code\Model\Specialist', 'Code\Specialist\SpecialistView', 'Code\Specialist\SpecialistController');
        $this->table['client'] = new Route('Code\Model\Client', 'Code\Client\ClientView', 'Code\Client\ClientController');
        $this->table['statistics'] = new Route('Code\Model\Statistics', 'Code\Statistics\StatisticsView', 'Code\Statistics\StatisticsController');
    }

    public function getRoute($route)
    {
        $route = strtolower($route);

        if (!isset($this->table[$route])) {
            return $this->table['default'];
        }

        return $this->table[$route];
    }
}
