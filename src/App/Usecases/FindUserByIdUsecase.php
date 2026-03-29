<?php

namespace Src\App\Usecases;

use Src\App\DTO\FindByIdDto;
use Src\App\Error\ApplicationError;
use Src\App\Error\UserNotFound;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

class FindUserByIdUsecase
{
    public function __construct(private UserRepository $userRepository)
    {
    }
    public function execute(string $id): User
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new UserNotFound($id);
        }
        return $user;
    }
}