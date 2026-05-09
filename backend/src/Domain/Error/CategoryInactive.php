<?php

namespace Src\Domain\Error;


class CategoryInactive extends \DomainException
{
    public function __construct()
    {
        parent::__construct("Category is inactive");
    }
}