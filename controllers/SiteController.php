<?php

namespace app\controllers;

use app\core\Controller;
use app\core\Application;
use app\core\Request;

class SiteController extends Controller
{

    public  function home()
    {
        $params = [
            'name' => "Yılmaz Kadan"
        ];
        return $this->render("home", $params);
    }
    public  function contact()
    {
        return $this->render("contact");
    }
    public  function handleContact(Request $request)
    {
        print_r($request->getBody());
    }
}
