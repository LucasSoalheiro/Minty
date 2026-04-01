<?php

namespace Src\App\Usecases;

use Src\App\DTO\AuthenticateDto;
use Src\App\Error\EmailNotFound;
use Src\App\Error\WrongPassword;
use Src\App\Security\TokenService;
use Src\Domain\User\PasswordHasher;
use Src\Domain\User\UserRepository;

class AuthenticateUsecase
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly PasswordHasher $passwordHasher,
        private readonly TokenService $tokenService
    ) {
    }

    public function execute(AuthenticateDto $dto): void
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if (!$user) {
            throw new EmailNotFound($dto->email);
        }
        if (!$this->passwordHasher->compare($dto->password, $user->getPassword()->value())) {
            throw new WrongPassword();
        }

        $token = $this->tokenService->generateToken($user);

    }
}