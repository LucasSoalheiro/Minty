<?php

namespace Src\App\DTO;

class CreateCategoryDto
{
    public function __construct(
        public string $name,
        public ?string $description,
        public string $userId
    ) {
    }
}