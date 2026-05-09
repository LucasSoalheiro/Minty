<?php
namespace Src\Domain\Error;

class NameShouldBeDifferent extends \DomainException
{
    public function __construct(string $name)
    {
        parent::__construct("Name should be different than the current one: $name");
    }
}