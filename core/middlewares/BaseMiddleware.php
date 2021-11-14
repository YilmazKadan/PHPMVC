<?php


namespace app\core\middlewares;

abstract class BaseMiddleware
{
    /**
     * Bu absctract metot herhangi bir middleware'ın çalışması için gerekli olan metotu miras bırakır .
     *
     * @return void
     */
    abstract public function execute();
}