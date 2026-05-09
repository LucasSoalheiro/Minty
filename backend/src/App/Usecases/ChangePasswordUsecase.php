<?php

namespace Src\App\Usecases;

use Src\App\DTO\ChangePasswordDto;
use Src\App\Error\EmailNotFound;
use Src\Domain\Repository\UserRepository;

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

        $user->changePassword($dto->oldPassword, $dto->newPassword);
        $this->userRepository->save($user);
    }
}