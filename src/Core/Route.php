<?php


namespace Code\Core;


class Route {

    private $model;
    private $view;
    private $controller;

    public function __construct($model, $view, $controller) {
        $this->model = $model;
        $this->view = $view;
        $this->controller = $controller;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getModel()
    {
        return $this->model;
    }
}
