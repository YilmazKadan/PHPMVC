<?php

namespace  app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    public string $layout = 'main';

    public string $action = '';
    /**
     * Undocumented variable
     *
     * @var array \app\core\middlewares\BaseMiddleware[]
     */
    public array $middlewares = [];
    public function  render($view, $params = [])
    {
        return Application::$app->router->renderView($view, $params);
    }
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddleWares():array{

        return $this->middlewares;
    }
}
