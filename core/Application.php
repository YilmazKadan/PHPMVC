<?php

namespace app\core;

class Application
{
    public static $ROOT_DIR;
    public Router $router;
    public Request $request;
    public Database $db;
    public Response $response;
    public Controller $controller;
    public static Application $app;
    public function __construct($rootPath, array $config)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->controller = new Controller();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);
    }
    public function run()
    {
        echo $this->router->resolve();
    }
}
