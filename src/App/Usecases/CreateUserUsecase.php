<?php

namespace Src\App\Usecases;

use Src\App\DTO\CreateUserDto;
use Src\App\Error\ApplicationError;
use Src\App\Error\EmailAlreadyInUse;
use Src\Domain\User\PasswordHasher;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\Email;

class CreateUserUsecase
{
    public function __construct(
        private UserRepository $userRepository,
        private PasswordHasher $passwordHasher
    ) {
    }
    public function execute(CreateUserDto $dto): void
    {
        if ($this->userRepository->findByEmail($dto->email) !== null) {
            throw new EmailAlreadyInUse($dto->email);
        }
        Password::validate($dto->password);
        $hashedPassword = $this->passwordHasher->hash($dto->password);

        $user = User::create(
            $dto->name,
            Email::create($dto->email),
            Password::restore($hashedPassword)
        );

        $this->userRepository->save($user);
    }
}