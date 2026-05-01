<?php

namespace Src\Infra\Http\Error;


class CookieException extends \RuntimeException
{
    public function __construct(string $message = "Cookie error")
    {
        parent::__construct($message);
    }
}