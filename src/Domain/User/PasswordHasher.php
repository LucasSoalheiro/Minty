<?php
namespace Src\Domain\User;

interface PasswordHasher{
    public function hash(string $password): string;
    public function compare(string $password, string $hash): bool;
}