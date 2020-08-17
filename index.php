<?
session_start();
use Code\Core\FrontController;
use Code\Core\Router;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/dependencyService.php';

$frontController = new FrontController(new Router, $_GET['route'], isset($_GET['action']) ? $_GET['action'] : null, $container);

