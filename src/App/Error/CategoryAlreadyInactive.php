<?php

namespace Src\App\Error;

class CategoryAlreadyInactive extends ApplicationError
{
    public function __construct(string $message = "Category is already inactive")
    {
        parent::__construct($message);
    }
}