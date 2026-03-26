<?php

namespace Src\Domain\Category\error;

class NameCannotBeNull extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Name cannot be null");
    }
}