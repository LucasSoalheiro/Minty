<?php
namespace Src\Infra\Db\User;

use Src\Domain\User\UserRepository;
use Src\Domain\User\User;
class UserDb implements UserRepository
{
  
    public function save(User $user): void
    {

    }
    public function findByEmail(string $email): ?User
    {
        
    }
    public function findById(string $uuid): ?User
    {
        return null;
    }
}