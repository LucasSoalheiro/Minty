<?php

namespace Src\App\DTO;

readonly class ChangeUserNameDto
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }

}