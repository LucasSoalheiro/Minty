<?php

namespace Tests\Domain\fake;

use Src\Domain\User\PasswordHasher;

class FakeHasher implements PasswordHasher
{
    public function hash(string $password): string
    {
        return "hashed_" . $password;
    }

    public function compare(string $password, string $hash): bool
    {
        return $hash === "hashed_" . $password;
    }
}