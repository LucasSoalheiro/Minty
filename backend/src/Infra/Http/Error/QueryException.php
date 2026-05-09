<?php

namespace Src\Infra\Http\Error;

class QueryException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}