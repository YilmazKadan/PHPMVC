<?php

namespace app\core;

use app\models\User;

class Application
{
    public static $ROOT_DIR;

    public string $userClass;
    public Router $router;
    public Request $request;
    public Database $db;
    public Session $session;
    public Response $response;
    public Controller $controller;
    // '?' property'nin türünün boş olabileceğini belirliyor 
    public ?DbModel $user;
    public static Application $app;
    public function __construct($rootPath, array $config)
    {
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->controller = new Controller();
        $this->session = new Session();
        $this->router = new Router($this->request, $this->response);

        $this->db = new Database($config['db']);

        $primaryValue = $this->session->get('user');

        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = NULL;
        }
    }
    public function run()
    {
        echo $this->router->resolve();
    }

    public function login(DbModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }
    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }
    /**
     * Bu metot user property sayesinde oturum açık olup olmadığını sorgular .
     * Oturum açık ise false döndürür .
     *
     * @return boolean
     */
    public static function isGuest(){
        return !self::$app->user;
    }
}
