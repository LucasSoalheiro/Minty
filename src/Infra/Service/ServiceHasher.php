<?php
namespace Src\Infra\Service;

use Src\Domain\Repository\Hasher;
class ServiceHasher implements Hasher {
 public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function compare(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
