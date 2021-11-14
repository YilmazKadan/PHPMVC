<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\models\ContactForm;

class SiteController extends Controller
{

    public  function home()
    {
        $params = [
            'name' => "Yılmaz Kadan"
        ];
        return $this->render("home", $params);
    }
    public  function contact(Request $request, Response $response)
    {
        $contact = new ContactForm();
        if ($request->isPost()) {

            $contact->loadData($request->getBody());

            if ($contact->validate() && $contact->save()) {
                Application::$app->session->setFlash('success', 'Mesajınız başarıyla gönderildi.');
                return $response->redirect("/");
            }
        }
        return $this->render('contact', [   
            'model' => $contact
        ]);
    }
}
