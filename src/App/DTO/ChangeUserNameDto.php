<?php

namespace Src\App\DTO;

class ChangeUserNameDto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }

}