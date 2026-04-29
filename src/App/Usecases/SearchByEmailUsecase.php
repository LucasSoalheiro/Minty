<?php

namespace Src\App\Usecases;

use Src\App\DTO\SearchByEmailResponse;
use Src\Domain\Repository\UserRepository;

class SearchByEmailUsecase
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }
    public function execute(string $email): SearchByEmailResponse
    {
        return new SearchByEmailResponse($this->userRepository->searchByEmail($email));
    }
}