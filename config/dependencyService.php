<?php

use Code\Client\ClientController;
use Code\Client\ClientView;
use Code\Core\Container;
use Code\Model\Client;
use Code\Model\JokeForm;
use Code\Model\JokeList;
use Code\Model\Queue;
use Code\Model\Registration;
use Code\Model\Specialist;
use Code\Model\Statistics;
use Code\Model\TimeManager;
use Code\Model\Token;
use Code\Queue\QueueController;
use Code\Queue\QueueView;
use Code\Registration\RegistrationController;
use Code\Registration\RegistrationView;
use Code\Specialist\SpecialistController;
use Code\Specialist\SpecialistView;
use Code\Statistics\StatisticsController;
use Code\Statistics\StatisticsView;

$container = new Container();
$container->set(
        Registration::class,
        function (Container $container) {
            return new \Code\Model\Registration(
                $container->get('PDO'),
                $container->get(TimeManager::class),
                $container->get(Token::class)
            );
        }
    )->set(
        RegistrationView::class,
        function (Container $container) {
            return new \Code\Registration\RegistrationView();
        }
    )->set(
        RegistrationController::class,
        function (Container $container) {
            return new \Code\Registration\RegistrationController();
        }
    )->set(
        Specialist::class,
        function (Container $container) {
            return new \Code\Model\Specialist(
                $container->get('PDO'),
                $container->get(TimeManager::class)
            );
        }
    )->set(
        SpecialistView::class,
        function (Container $container) {
            return new \Code\Specialist\SpecialistView();
        }
    )->set(
        SpecialistController::class,
        function (Container $container) {
            return new \Code\Specialist\SpecialistController();
        }
    )->set(
        Queue::class,
        function (Container $container) {
            return new \Code\Model\Queue(
                $container->get('PDO'),
                $container->get(TimeManager::class)
            );
        }
    )->set(
        QueueView::class,
        function (Container $container) {
            return new \Code\Queue\QueueView();
        }
    )->set(
        QueueController::class,
        function (Container $container) {
            return new \Code\Queue\QueueController();
        }
    )->set(
        Client::class,
        function (Container $container) {
            return new \Code\Model\Client(
                $container->get('PDO'),
                $container->get(TimeManager::class),
                $container->get(Queue::class)
            );
        }
    )->set(
        ClientView::class,
        function (Container $container) {
            return new \Code\Client\ClientView();
        }
    )->set(
        ClientController::class,
        function (Container $container) {
            return new \Code\Client\ClientController();
        }
    )->set(
        Statistics::class,
        function (Container $container) {
            return new \Code\Model\Statistics(
                $container->get('PDO'),
                $container->get(TimeManager::class)
            );
        }
    )->set(
        StatisticsView::class,
        function (Container $container) {
            return new \Code\Statistics\StatisticsView();
        }
    )->set(
        StatisticsController::class,
        function (Container $container) {
            return new \Code\Statistics\StatisticsController();
        }
    )->set(
        Token::class,
        function (Container $container) {
            return new \Code\Model\Token();
        }
    )->set(
        'PDO',
        function () {
            return new PDO('mysql:host=db;dbname=test_db', 'devuser', 'devpass', array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
        }
    )->set(
        TimeManager::class,
        function (Container $container) {
            return new Code\Model\TimeManager(
                $container->get('PDO')
            );
        }
    );

