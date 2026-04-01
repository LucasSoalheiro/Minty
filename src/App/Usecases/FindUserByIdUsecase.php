<?php

namespace Src\App\Usecases;

use Src\App\DTO\FindByIdDto;
use Src\App\DTO\UserResponseDto;
use Src\App\Error\ApplicationError;
use Src\App\Error\UserNotFound;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;

class FindUserByIdUsecase
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }
    public function execute(string $id): UserResponseDto
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new UserNotFound($id);
        }
        return new UserResponseDto(
            $user->getId()->__toString(),
            $user->getName(),
            $user->getEmail()
        );
    }
}