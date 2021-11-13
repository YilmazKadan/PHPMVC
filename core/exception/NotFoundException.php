<?php


namespace app\core\exception;

class NotFoundException extends \Exception
{
    protected $message = "Erişmeye çalıştığınız sayfa bulunamadı !";
    protected $code = 404;
}
