<?php


namespace app\core\middlewares;

use app\core\Application;
use app\core\exception\ForbiddenException;

/**
 * AuthMiddleware sınıfı 
 * 
 * Bu sınıf 
 */
class AuthMiddleware extends BaseMiddleware
{
    /**
     * Bu metot hangi hedef url'lerin middleware uygulanacağını belirler.
     *
     * @param array $actions bu array'in içinde belirtilen url'lerin middleware uygulanacağını belirtir.
     */
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
    /**
     * @var array $actions Bu array'in içinde belirtilen url'lerin middleware uygulanacağını belirtir.
     */
    public array $actions = [];

    /**
     * Bu metot oturum açılmadan erişilebilen url'leri belirler
     * 
     * Bu metot çağrıldıüı controller sınıf içerisinde tanımlanmış olan $actions değişkeni empty olarak tanımlanmış ise
     * tüm url'lere erişimi engeller . 
     * 
     * Bunun yanı sıra bir array gelir ise ve aktif olarak erişilmeye çalışılan url daha önce bu middleware
     * actions'ları içerisinde bulunuyor ise o url'e erişim engellenir ve bir 403 hatası fırlatılır.
     *
     * @return void
     */
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) ||  in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}
