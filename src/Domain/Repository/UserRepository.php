<?php

namespace Src\Domain\Repository;

use Src\Domain\Entities\User;

interface UserRepository
{
    public function save(User $user): void;
    public function findByEmail(string $email): ?User;
    /**
     * Summary of searchByEmail
     * @param string $email
     * @return User[]
     */
    public function searchByEmail(string $email): array;
    public function findById(string $id): ?User;
}