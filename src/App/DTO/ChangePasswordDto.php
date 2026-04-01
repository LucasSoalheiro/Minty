<?php

namespace Src\App\DTO;

class ChangePasswordDto
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }

}