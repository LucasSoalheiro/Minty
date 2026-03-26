<?php

namespace Src\Domain\ValueObject;

use Src\Domain\Error\InvalidEmail;


class Email
{

    private function __construct(private string $email)
    {
    }

    public static function create(string $email): self
    {
        if (!self::validate($email)) {
            throw new InvalidEmail($email);
        }
        return new self($email);
    }

    public static function restore(string $email): self
    {
        if (!self::validate($email)) {
            throw new InvalidEmail($email);
        }
        return new self($email);
    }

    public function equals(Email $other): bool
    {
        return $this->email === $other->email;
    }
    
    public function __toString(): string
    {
        return $this->email;
    }
    private static function validate(string $email): bool
    {
        return filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) !== false;
    }
}