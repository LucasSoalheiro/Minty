<?php

namespace Src\App\Error;

class ApplicationError extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}