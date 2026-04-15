<?php

namespace Src\App\Usecases;

use Src\App\DTO\CreateUserDto;
use Src\App\Error\EmailAlreadyInUse;
use Src\Domain\Entities\User;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\Email;

class CreateUserUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Hasher $passwordHasher
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