<?php

class Router
{
    private $controller;
    private $method;

    public function __construct()
    {
        $this->matchRoute();
    }

    public function matchRoute()
    {

        $url = explode('/', URL);

        $this->controller = !empty($url[1]) ? $url[1] : 'page';
        $this->method = !empty($url[2]) ? $url[2] : 'home';

        $this->controller = $this->controller . 'Controller';
        require_once(__DIR__ . '/Controller/' . $this->controller . '.php');
    }

    public function run()
    {
        $session = new Session();
        $conexion = new Connection();
        $connect = $conexion->getConnection();
        $controller = new $this->controller($connect, $session);
        $method = $this->method;
        $controller->$method();
    }
}
