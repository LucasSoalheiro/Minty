<?php
namespace Src\Domain\User\error;

class InvalidPassword extends \Exception
{
    public function __construct()
    {
        parent::__construct("Invalid password");
    }
}