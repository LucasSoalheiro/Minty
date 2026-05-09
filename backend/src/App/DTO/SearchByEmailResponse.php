<?php

namespace Src\App\DTO;

readonly class SearchByEmailResponse
{
    /**
     * @param UserResponseDto[] $users
     */
    public function __construct(public array $users)
    {
    }
}