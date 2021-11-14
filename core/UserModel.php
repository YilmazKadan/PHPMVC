<?php

namespace app\core;

abstract class UserModel extends DbModel
{
    /**
     * User'ın adını ve soyadını döndürecek abstract metot.
     *
     * @return string
     */
    abstract public function getDisplayName(): string;
}
