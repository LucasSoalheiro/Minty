<?php

namespace Src\App\DTO;

class SearchByEmailResponse
{
    /**
     * @param UserResponseDto[] $users
     */
    public function __construct(public array $users)
    {
    }
}