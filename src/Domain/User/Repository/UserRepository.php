<?php

namespace Src\Domain\User\Repository;

use Src\Domain\User\User;

interface UserRepository
{
    public function save(User $user): void;
    public function findByEmail(string $email): ?User;
    public function findByUuid(string $uuid): ?User;
}