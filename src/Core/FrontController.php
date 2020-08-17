<?php


namespace Code\Core;

use Code\Core\Container;
use Code\JokeList\ListController;
use Code\JokeList\ListView;
use Code\JokeForm\FormController;
use Code\JokeForm\FormView;
use Code\Model\JokeList;
use Code\Model\JokeForm;
use Code\Core\Router;
use Code\Core\Route;
use PDO;

class FrontController
{

    private $controller;
    private $view;


    public function __construct(Router $router, $routeName, $action, $container)
    {
        //gaunam is lenteles modeliu view ir controleriu pavadinimus
        $route = $router->getRoute($routeName);

        //setinam pavadinimus is route objekto i kintamuosius
        $modelName = $route->getModel();
        $controllerName = $route->getController();
        $viewName = $route->getView();

        //sukuriam kontroleri ir paduodam atitinkama modeli
        $this->controller = $container->get($controllerName);

        //sukuriam view $routName nereikia
        $this->view = $container->get($viewName);

        //parenkam controlerio veiksma, jei jis nurodytas url GET
//        $model = (!empty($action)) ? $this->controller->{$action}($container->get($modelName)) : $this->controller->{$routeName}($container->get($modelName));
        if(!empty($action)) {
            $model = $this->controller->{$action}($container->get($modelName));
        } else if (!empty($routeName && method_exists($this->controller,$routeName))){
            $model = $this->controller->{$routeName}($container->get($modelName));
        } else {
            $model = $container->get($modelName);
        }

        //isprintinam view
        $this->output($model);

    }

    public function output($model)
    {
        $header = file_get_contents('public/html/header.html');
        $footer = file_get_contents('public/html/footer.html');
        echo $header . '<div>' . $this->view->output($model) . '</div>' . $footer;
    }

}
