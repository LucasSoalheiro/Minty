<?php
namespace Src\Infra\Db\User;

use Src\Domain\User\Repository\UserRepository;
use Src\Domain\User\User;
class UserDb implements UserRepository
{
  
    public function save(User $user): void
    {

    }
    public function findByEmail(string $email): ?User
    {
    }
    public function findByUuid(string $uuid): ?User
    {
    }
}