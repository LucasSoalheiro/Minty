<?php
namespace Src\Domain\User\Error;

class InvalidPassword extends \Exception
{
    public function __construct()
    {
        parent::__construct("Invalid password");
    }
}