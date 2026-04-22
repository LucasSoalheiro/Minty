<?php

namespace Src\App\Usecases;

use Src\App\DTO\LoginDto;
use Src\App\DTO\LoginResponseDto;
use Src\App\Error\EmailNotFound;
use Src\App\Error\WrongPassword;
use Src\App\Security\TokenService;
use Src\Domain\Entities\Session;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\Repository\UserRepository;

class LoginUsecase
{
    public function __construct(
        private readonly SessionRepository $sessionRepository,
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenService
    ) {
    }
    public function execute(LoginDto $dto): LoginResponseDto
    {
        $user = $this->userRepository->findByEmail($dto->email);
        if (!$user) {
            throw new EmailNotFound($dto->email);
        }
        if (!$user->passwordMatch($dto->password)) {
            throw new WrongPassword();
        }

        $refreshToken = bin2hex(random_bytes(32));

        $session = Session::create($user->id, $refreshToken);

        $this->sessionRepository->save($session);

        $accessToken = $this->tokenService->generateToken($user);
        return new LoginResponseDto($accessToken, $refreshToken);

    }
}