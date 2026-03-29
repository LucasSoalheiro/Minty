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
        try {
            if ($this->userRepository->findByEmail($dto->getEmail()) !== null) {
                throw new EmailAlreadyInUse($dto->getEmail());
            }
            $hashedPassword = $this->passwordHasher->hash(Password::validate($dto->getPassword()));

            User::create($dto->getName(), Email::create($dto->getEmail()), Password::restore($hashedPassword));

        } catch (ApplicationError $e) {
            throw new ApplicationError("Error creating user: " . $e->getMessage());
        }
    }
}