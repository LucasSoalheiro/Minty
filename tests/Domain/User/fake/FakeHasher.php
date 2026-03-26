<?php
namespace Tests\Domain\User\Fake;

use Src\Domain\User\PasswordHasher;

class FakeHasher implements PasswordHasher
{
    public function hash(string $password): string
    {
        return $password;
    }

    public function compare(string $password, string $hash): bool
    {
        return $password === $hash;
    }
    
}