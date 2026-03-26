<?php

namespace Src\Domain\User;

interface UserRepository
{
    public function save(User $user): void;
    public function findByEmail(string $email): ?User;
    public function findById(string $id): ?User;
}