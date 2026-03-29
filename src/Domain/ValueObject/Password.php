<?php
namespace Src\Domain\ValueObject;

use Src\Domain\Error\InvalidPassword;
use Src\Domain\Error\WeakPassword;
use Src\Domain\User\PasswordHasher;

final class Password
{
    private function __construct(
        private string $hashedPassword
    ) {
    }

    public static function restore(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }
    
    public function value(): string
    {
        return $this->hashedPassword;
    }

    public static function validate(string $password): void
    {
        if (empty($password)) {
            throw new InvalidPassword();
        }
        if (\strlen($password) < 8) {
            throw new WeakPassword($password);
        }

        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            throw new WeakPassword($password);
        }
    }
}