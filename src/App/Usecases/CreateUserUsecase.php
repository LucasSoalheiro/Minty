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
    public function __construct(private UserRepository $userRepository, private PasswordHasher $passwordHasher)
    {
    }
    public function execute(CreateUserDto $dto): void
    {
        if ($this->userRepository->findByEmail($dto->getEmail()) !== null) {
            throw new EmailAlreadyInUse($dto->getEmail());
        }
        Password::validate($dto->getPassword());
        $hashedPassword = $this->passwordHasher->hash($dto->getPassword());

        $user = User::create($dto->getName(), Email::create($dto->getEmail()), Password::restore($hashedPassword));

        $this->userRepository->save($user);
    }
}