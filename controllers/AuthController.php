<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\middlewares\AuthMiddleware;
use app\core\Request;
use app\core\Response;
use app\models\LoginForm;
use app\models\User;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['profile']));
    }
    // Login method
    public function login(Request $request, Response $response)
    {
        $loginForm = new LoginForm();

        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect('/');
                return;
            }
        }
        $this->setLayout('auth');
        return $this->render('login', [
            "model" => $loginForm
        ]);
    }
    /**
     * User çıkış metotu
     * 
     * Bu metot Application sınıfından logout metodunu çağırır.
     * Ve çıkış yapıldıktan sonra kullanıcıya yönlendirilir.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function logout(Request $request ,Response $response){
        Application::$app->logout();
        $response->redirect('/');
    }
    /**
     * Kayıt metotu
     * 
     * Post işlemi var ise kayıt işlemleri yok ise kayıt formu gösterilir.
     * Bu metot User modelinin save metodunu çağırır.
     * Kayıt işlemi başarılı ise kullanıcıya yönlendirilir. Değil ise kayıt formu ve hatalar gösterilir.
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $this->setLayout('auth');

        $User  = new User();
        if ($request->isPost()) {

            $User->loadData($request->getBody());
            if ($User->validate() && $User->save()) {
                Application::$app->session->setFlash('success', 'Kayıt olduğunuz için teşekkür ederiz .');
                Application::$app->response->redirect('/');
            }

            return $this->render('register', [
                'model' => $User
            ]);
        }
        return $this->render('register', [
            'model' => $User
        ]);
    }
    /**
     * Profil metotu
     * 
     * Bu metot profil sayfasını yükler
     *
     * @return void
     */
    public function profile(){

        return $this->render('profile');
    }
}
