<?php

namespace Src\App\DTO;

readonly class ListAccountResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public int $balance,
        public bool $isActive,
    )
    {
    }
}