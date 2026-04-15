<?php

namespace Tests\fake;

use Src\Domain\Repository\Hasher;

class FakeHasher implements Hasher
{
    public function hash(string $password): string
    {
        return "hashed_$password";
    }

    public function compare(string $password, string $hash): bool
    {
        return $hash === "hashed_$password" ;
    }
}