<?php

namespace Src\App\Usecases;

use Src\App\DTO\ChangeUserNameDto;
use Src\App\Error\UserNotFound;
use Src\App\Error\WrongPassword;
use Src\Domain\User\PasswordHasher;
use Src\Domain\User\UserRepository;

class ChangeUserNameUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasher $passwordHasher
    ) {
    }

    public function execute(ChangeUserNameDto $dto): void
    {
        $user =  $this->userRepository->findByEmail($dto->email);
        if (!$user) {
            throw new UserNotFound($dto->email);
        }
        if (!$this->passwordHasher->compare($dto->password, $user->getPassword()->value())) {
            throw new WrongPassword();
        }
        $user->changeName($dto->name);
        $this->userRepository->save($user);
    }
}   