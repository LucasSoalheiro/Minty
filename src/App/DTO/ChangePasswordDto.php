<?php

namespace Src\App\DTO;

readonly class ChangePasswordDto
{
    public function __construct(
        public string $email,
        public string $password,
        public string $newPassword
    ) {
    }

}