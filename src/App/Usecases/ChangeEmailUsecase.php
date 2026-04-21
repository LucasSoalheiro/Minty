<?php

namespace Src\App\Usecases;

use Src\App\DTO\ChangeEmailDto;
use Src\App\Error\EmailAlreadyInUse;
use Src\App\Error\UserNotFound;
use Src\App\Error\WrongPassword;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;

class ChangeEmailUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly Hasher $passwordHasher
    ) {
    }

    public function execute(ChangeEmailDto $dto): void
    {
        $user = $this->userRepository->findById($dto->id);
        if (!$user) {
            throw new UserNotFound($dto->id);
        }

        if ($this->userRepository->findByEmail($dto->email)) {
            throw new EmailAlreadyInUse($dto->email);
        }

        if (!$this->passwordHasher->compare($dto->password, $user->password->value())) {
            throw new WrongPassword();
        }

        $user->changeEmail(Email::create($dto->email));

        $this->userRepository->save($user);
    }
}