<?php

namespace app\core;

use app\core\db\Database;

/**
 * Apllication sınıfı 
 * 
 * Bu sınıf  uygulamanın ana kısmını yönetir.
 * Diğer tüm sınıflar arasındaki ana bağlantı burasıdır . Tüm işlemler buradan dağılır
 */
class Application
{
    /**
     * @var string $ROOT_DIR bu değişken site kök dizininin adresini tutar.
     */
    public static $ROOT_DIR;

    /**
     * User sınıfının nesnesini tutar.
     *
     * @var string  $userClass
     */
    public string $userClass;
    /**
     * Router sınıfından bir nesne tutar
     *
     * @var Router
     */
    public Router $router;
    /**
     * Request sınıfından bir nesne tutar
     *
     * @var Request $request sınıfından bir nesne.
     */
    public Request $request;
    /**
     * Database sınıfından bir nesne tutar
     *
     * @var Database $db Database sınıfından bir nesne
     */
    public Database $db;
    /**
     * Session sınıfından bir nesne tutar
     *
     * @var Session $session Session sınıfından bir nesne
     */
    public Session $session;
    /**
     * Response sınıfından bir nesne tutar
     *
     * @var Response $response
     */
    public Response $response;
    /**
     * @var Controller $controller aktif olarak çalışılan controller nesnesini tutar .
     */
    public Controller $controller;

    public View $view;
    // '?' property'nin türünün boş olabileceğini belirliyor 

    /**
     * DbModel sınıfından miras alan bir user nesnesi tutar.
     *
     * @var UserModel|null $user aktif olarak oturum açan kullanıcının bilgilerini tutar.
     */
    public ?UserModel $user;
    /**
     * Undocumented variable
     *
     * @var Application $app Application sınıfının statik olmayan değişkenlerine 
     * ve metotlarına erişmek için Application sınıfından bir nesne tutar.
     */
    public static Application $app;

    /**
     * Application sınıfı yapıcı metotu
     * 
     * Bu metot tüm alt sınıflardan instance'ler oluşturur ve bunların gerekli olanlarına 
     * parametreler yollar .
     *
     * @param [type] $rootPath Bu değişken site kök dizininin adresini tutar.
     * @param array $config Bu değişken site ayarlarını tutar.
     */
    public function __construct($rootPath, array $config)
    {
        $this->session = new Session();
        $primaryValue = $this->session->get('user');
        $this->userClass = $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->controller = new Controller();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
        $this->db = new Database($config['db']);


        if ($primaryValue) {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = NULL;
        }
    }
    /**
     * Tüm router işlemlerini başlatan ve response hataları yakalayan metot.
     * 
     * Bu metot  tüm routing ve response işlemlerini başlatır .
     *
     * @return void
     */
    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (\Exception $e) {
            // Hata response hatası ise bu metot çalışır.
            if (get_parent_class($e) == "Exception") {

                $this->response->setStatusCode($e->getCode());
            }
            echo $this->view->renderView('_error', [
                "exception" =>  $e
            ]);
        }
    }
    /**
     * Login işlemini yapar.
     * 
     * Bu metot , giriş işlemi başarılı ile gerçekleştikten sonra çalışır ve 
     * kullanıcının giriş yaptığı nesneyi alıp session'a atar. Ve gerekli bilgileri buradaki property'lere aktarır .
     * 
     * @param DbModel $user bu parametre kullanıcının nesnesini tutar.
     * @return bool true dönerse başarılı bir şekilde giriş yapıldı.
     */
    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user', $primaryValue);
        return true;
    }
    /**
     * Çıkış metotu
     * 
     * Bu metot kullanıcının çıkış yaptığı anda çalışır ve user  session'ını siler.
     *
     * @return void
     */
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
    public static function isGuest()
    {
        return !self::$app->user;
    }
}
