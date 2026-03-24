<?php

namespace Src\Domain\User\VO;


class Email
{

    private function __construct(private string $email)
    {
    }

    public static function create(string $email): Email
    {
        if (!self::validate($email)) {
            throw new \ErrorException("Invalid email format: $email");
        }
        return new Email($email);
    }

    public static function restore(string $email): Email
    {
        return new Email($email);
    }
    public function __toString(): string
    {
        return $this->email;
    }
    private static function validate(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}