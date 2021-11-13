<?php


namespace app\core\exception;

class ForbiddenException extends \Exception
{
    protected $message = "Bu sayfaya erişiminiz bulunmamaktadır";
    protected $code = 403;
}
