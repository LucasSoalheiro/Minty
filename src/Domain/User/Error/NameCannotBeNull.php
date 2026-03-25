<?php
namespace Src\Domain\User\Error;
class NameCannotBeNull extends \Exception
{
    public function __construct()
    {
        parent::__construct("Name cannot be null or empty");
    }
}