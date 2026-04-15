<?php

namespace Src\App\Usecases;

use Src\App\DTO\ChangeUserNameDto;
use Src\App\Error\UserNotFound;
use Src\Domain\Repository\UserRepository;

class ChangeUserNameUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function execute(ChangeUserNameDto $dto): void
    {
        $user =  $this->userRepository->findByEmail($dto->email);
        if (!$user) {
            throw new UserNotFound($dto->email);
        }
        
        $user->changeName($dto->name);
        $this->userRepository->save($user);
    }
}   