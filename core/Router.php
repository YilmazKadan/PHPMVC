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
            return $this->renderView($callback);
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
    /**
     * View'i render eder.
     *
     * layoutConent ile gelen çıktı ve renderOnlyView ile gelen çıktıları {{content}} içerisine yerleştirir.
     * @param [string] $view view ismi
     * @return string view'i render edilen html stringi döner.
     */
    public function renderView($view , $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    /**
     * layoutContent metotu
     * 
     * Bu metot controller'da belirtilen layout dosyasının içeriğinin ob_start() ile çıktıyı alır.
     * Ve sonrasında ob_get_clean() ile çıktıyı döndürür.
     *
     * @return string ob_get_clean() ile döndürülen layout dosyasının içeriği çıktı olarak döner .
     */
    protected function layoutContent()
    {   $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    /**
     * renderOnlyView metotu
     * 
     * Bu metot controller'da belirtilen view dosyasının içeriğinin ob_start() ile çıktıyı alır. Ama 
     * ob_get_clean() ile çıktıyı döndürmez. Layout sayfasının içerisine yerleştirleceği için değer döndürülmez.
     *
     * @param [string] $view view ismi
     * @param [string] $params view sayfasında kullanılacak parametreleri tutar.
     * @return string view'i render edilen html stringi döner.
     */
    protected function renderOnlyView($view,$params)
    {
        foreach ($params as $key => $value){
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}
