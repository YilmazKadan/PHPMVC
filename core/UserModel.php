<?php

namespace app\core;
use app\core\db\DbModel;

abstract class UserModel extends DbModel
{
    /**
     * User'ın adını ve soyadını döndürecek abstract metot.
     *
     * @return string
     */
    abstract public function getDisplayName(): string;
}
