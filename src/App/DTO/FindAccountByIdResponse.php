<?php

namespace Src\App\DTO;

class FindAccountByIdResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public int $balance,
        public string $userId
    ) {
    }
}