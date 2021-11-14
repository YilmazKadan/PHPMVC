<?php

namespace  app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{
    /**
     * 
     *
     * @var string $layout Bu değişken controller içerisinde kullanılacak layout dosyasını belirtir.
     */
    public string $layout = 'main';

    /**
     * Undocumented variable
     *
     * @var string $action Bu değişken controller içerisinde kullanılacak aktif action (Login, register vb.) metodunu belirtir.
     */
    public string $action = '';
    /**
     * Undocumented variable
     *
     * @var array \app\core\middlewares\BaseMiddleware[]
     */
    public array $middlewares = [];
    /**
     * Render Metodu
     * 
     * Bu metot router sınıfının içerisindeki renderView metodunun kısa yoludur..
     *
     * @param [string] $view layout ve view dosyalarının yolu
     * @param array $params view dosyasına gönderilecek değişkenler
     * @return void
     */
    public function  render($view, $params = [])
    {
        return Application::$app->view->renderView($view, $params);
    }
    /**
     * Layout değiştirme metotu
     * 
     * Bu metot controller içerisinde kullanılacak layout değiştirmeyi sağlar.
     *
     * @param [string] $layout layout dosyasının yolu.
     * @return void
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }
    /**
     * Undocumented function
     *
     * @return array \app\core\middlewares\BaseMiddleware[] tüm middleware'lere nesnelerini döndürür .
     */
    public function getMiddleWares():array{

        return $this->middlewares;
    }
}
