<?php

namespace Src\App\Usecases;

use Src\App\DTO\ChangeEmailDto;
use Src\App\Error\EmailNotFound;
use Src\App\Error\WrongPassword;
use Src\Domain\User\PasswordHasher;
use Src\Domain\User\UserRepository;
use Src\Domain\ValueObject\Email;

class ChangeEmailUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasher $passwordHasher
    ) {
    }

    public function execute(ChangeEmailDto $changeEmailDto): void
    {
        $user = $this->userRepository->findByEmail($changeEmailDto->email);

        if (!$user) {
            throw new EmailNotFound($changeEmailDto->email);
        }

        if (!$this->passwordHasher->compare($changeEmailDto->password, $user->getPassword()->value())) {
            throw new WrongPassword();
        }

        $user->changeEmail(Email::create($changeEmailDto->email));

        $this->userRepository->save($user);
    }
}