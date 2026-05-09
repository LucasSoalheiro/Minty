<?php

namespace Src\Infra\Http\Error;

use Symfony\Component\Validator\Exception\InvalidArgumentException;

class ValidatorException extends InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}