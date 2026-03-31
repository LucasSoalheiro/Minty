<?php

namespace Src\App\Error;

class CategoryNotFound extends ApplicationError
{
    public function __construct(string $categoryId)
    {
        parent::__construct("Category with ID $categoryId not found.");
    }
}