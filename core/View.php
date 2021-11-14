<?php

namespace app\core;

class View{

    /**
     * Sayfa başlıklarını tutan değişken
     * 
     * Bu  property çağrılan sayfaların başında güncellenir ise sayfa title özelliği ona göre değişir
     *
     * @var string
     */
    public string $title  = '';

    /**
     * Sayfada gösterilecek anahtar kelimelerin tutulduğu değişken .
     * 
     * @var string
     */
    public string $keywords = '';

       /**
     * View'i render eder.
     *
     * layoutConent ile gelen çıktı ve renderOnlyView ile gelen çıktıları {{content}} içerisine yerleştirir.
     * @param [string] $view view ismi
     * @return string view'i render edilen html stringi döner.
     */
    public function renderView($view , $params = [])
    {
        /**
         * ilk olarak araya eklenecek view çağrılır . Çağrılan view dosyasının 
         * başında $this->title özelliği değiştirilir . Amaç 'title' ve benzeri değişkenleri view'e dahil etmek.
         */
        $viewContent = $this->renderOnlyView($view, $params);
        $layoutContent = $this->layoutContent();
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