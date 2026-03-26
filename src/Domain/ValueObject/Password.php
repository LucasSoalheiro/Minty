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

    public static function create(string $plainPassword, PasswordHasher $hasher): self
    {
        
        self::validate($plainPassword);

        return new self($hasher->hash($plainPassword));
    }

    public static function restore(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function verify(string $plainPassword, PasswordHasher $hasher): bool
    {
        return $hasher->compare($plainPassword, $this->hashedPassword);
    }

    public function equals(Password $other): bool
    {
        return $this->hashedPassword === $other->hashedPassword;
    }
    public function __tostring(): string
    {
        return $this->hashedPassword;
    }

    private static function validate(string $password): void
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