<?php
namespace Src\Domain\User\VO;

use Src\Domain\User\Repository\PasswordHasher;

class Password
{
    private function __construct(
        private string $password
    ) {
    }

    public static function create(string $password, PasswordHasher $hasher): Password
    {
        $hashedPassword = $hasher->hash($password);
        return new Password($hashedPassword);
    }

    public static function restore(string $hashedPassword): Password
    {
        return new Password($hashedPassword);
    }
    
    public function __toString(): string
    {
        return $this->password;
    }
}