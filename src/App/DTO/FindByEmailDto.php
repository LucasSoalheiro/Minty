<?php

namespace Src\App\DTO;

class FindByEmailDto
{
    public function __construct(private string $email)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}