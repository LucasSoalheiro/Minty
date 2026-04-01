<?php

namespace Src\App\Error;

class CategoryInactive extends ApplicationError
{
    public function __construct(string $message = "Category is inactive")
    {
        parent::__construct($message);
    }
}