<?php
namespace Src\Domain\User\error;

class NameShouldBeDifferent extends \Exception
{
    public function __construct(string $name)
    {
        parent::__construct("Name should be different than the current one: $name");
    }
}