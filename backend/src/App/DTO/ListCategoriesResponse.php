<?php

namespace Src\App\DTO;

readonly class ListCategoriesResponse
{

    public function __construct(
        public string $id,
        public string $name,
        public ?string $description, 
        public bool $isActive
    ) {
    }
}
