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
    ) {
    }

    public function execute(ChangePasswordDto $dto): void
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user) {
            throw new EmailNotFound($dto->email);
        }

        $user->changePassword($dto->password, $dto->newPassword);
        $this->userRepository->save($user);
    }
}