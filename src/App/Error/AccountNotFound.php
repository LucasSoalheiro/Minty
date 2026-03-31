<?php

namespace Src\App\Error;

class AccountNotFound extends ApplicationError
{
    public function __construct(string $accountId)
    {
        parent::__construct("Account with ID $accountId not found.");
    }
}