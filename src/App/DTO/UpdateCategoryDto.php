<?php

namespace Src\App\DTO;

readonly class UpdateCategoryDto
{
    public function __construct(
        public string $id,
        public ?string $name,
        public ?string $description,
    ) {
    }
}