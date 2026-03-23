<?php
namespace Src\Infra\Service;

use Src\Domain\User\Repository\PasswordHasher;

class Hasher implements PasswordHasher {
 public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function compare(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
