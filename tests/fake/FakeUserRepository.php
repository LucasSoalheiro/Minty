<?php

namespace Tests\fake;

use Src\Domain\Entities\User;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\UUID;

final class FakeUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private array $users = [];

    public function save(User $user): void
    {
        $this->users[] = $user;
    }

    public function searchByEmail(string $email): array
    {
        return array_filter($this->users, fn($u) => str_contains($u->email->__toString(), $email));
    }

    public function findByEmail(string $email): ?User
    {
        return array_find($this->users, fn($u) => $u->email->equals(Email::create($email)));
    }

    public function findById(string $id): ?User
    {
        return array_find($this->users, fn($u) => $u->id->equals(UUID::fromString($id)));
    }
}