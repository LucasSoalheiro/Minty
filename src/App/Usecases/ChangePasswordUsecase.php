<?php

namespace Src\App\Usecases;

use Src\App\DTO\ChangePasswordDto;
use Src\App\Error\EmailNotFound;
use Src\App\Error\WrongPassword;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Password;

class ChangePasswordUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Hasher $passwordHasher
    ) {
    }

    public function execute(ChangePasswordDto $dto): void
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new EmailNotFound($dto->email);
        }

        if (!$this->passwordHasher->compare($dto->password, $user->getPassword()->value())) {
            throw new WrongPassword();
        }
        Password::validate($dto->newPassword);
        $user->changePassword($this->passwordHasher->hash($dto->newPassword));
        $this->userRepository->save($user);
    }
}