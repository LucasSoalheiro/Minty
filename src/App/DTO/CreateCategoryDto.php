<?php

namespace Src\App\DTO;

readonly class CreateCategoryDto
{
    public function __construct(
        public string $name,
        public ?string $description,
        public string $userId
    ) {
    }
}