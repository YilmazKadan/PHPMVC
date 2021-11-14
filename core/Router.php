<?php

namespace app\core;

use app\core\exception\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    /**
     * Tüm route'ları tutmaya yarayan array.
     * @var array $routes[] tüm rotaları tutar
     */
    protected array $routes = [];

    /**
     * Router constructor.
     * @param Request $request request nesnesi
     * @param Response $response response nesnesi
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    /**
     * Get isteklerini yakalayan ve routes arrayine atan methotdur.
     *
     * @param [string] $path istek yolunu belirtir.
     * @param [array]|[string] $callback controller veya direkt view ismi alır .
     * @return void
     */
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    /**
     * Post isteklerini yakalayan ve routes arrayine atan methotdur.
     *
     * @param [string] $path istek yolunu belirtir .
     * @param [array]|string $callback controller veya direkt view ismi alır .
     * @return void
     */
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }
    /**
     * resolve metotu ile istekte bulunan url'yi kontrol eder ve callback'ı çalıştırır
     * 
     * Bu metot sayesinde istekte bulunan url'yi kontrol eder ve callback'ı çalıştırır.
     * routes dizisine kayıtlı istekleri kontrol eder.
     * Eğer url'ye uygun bir callback bulunamazsa NotFoundException fırlatılır.
     *
     * Eğer var ise callback metotunu çalıştırır ve paramatre olarak istek ve cevap nesnesi gönderir.
     * @return void
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if ($callback === false) {
            throw new NotFoundException();
        }
        /**
         * $callback string ise direkt view'e gönderir.
         */
        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }
        if(is_array($callback)){
            /**
             * @var \app\core\Controller $controller
             */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;

            /**
             * Bu kısım gelen controllara ait tüm middlewareleri çalıştırır
             * Bu döngü sayesinde gidilecek controller'e ait tüm middlewareleri çalıştırır
             * @var \app\core\Middleware $middleware çalıştırılacak middleware'leri içerir .
             */
            foreach ($controller->getMiddleWares() as $middleware){
                $middleware->execute();
            }
        }

        return call_user_func($callback,$this->request, $this->response);
    }
}
