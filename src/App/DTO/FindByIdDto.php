<?php

namespace Src\App\DTO;

class FindByIdDto
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}