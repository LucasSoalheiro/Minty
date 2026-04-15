<?php

namespace Tests\fake;

use Src\Domain\Entities\User;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\UUID;

class FakeUserRepository implements UserRepository
{
    private array $users = [];
    
    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getEmail()->equals(Email::create($email))) {
                return $user;
            }
        }
        return null;
    }

    public function findById(string $id): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getId()->equals(UUID::fromString($id))) {
                return $user;
            }
        }
        return null;
    }
}