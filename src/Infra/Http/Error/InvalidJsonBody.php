<?php

namespace Src\Infra\Http\Error;


class InvalidJsonBody extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct("Invalid JSON body");
    }
}