<?php

namespace Src\Domain\Category\error;

class InvalidDescription extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("Invalid description");
    }
}