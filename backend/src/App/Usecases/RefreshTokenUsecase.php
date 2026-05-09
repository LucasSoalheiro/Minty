<?php

namespace Src\App\Usecases;

use Src\App\DTO\RefreshTokenResponse;
use Src\App\Error\InvalidRefreshToken;
use Src\App\Error\SessionNotFound;
use Src\App\Security\TokenService;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\Repository\UserRepository;

class RefreshTokenUsecase
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private TokenService $tokenService,
        private UserRepository $userRepository
    ) {
    }

    public function execute(string $refreshtoken): RefreshTokenResponse
    {
        $session = $this->sessionRepository->findByToken($refreshtoken);
        if (!$session) {
            throw new SessionNotFound($refreshtoken);
        }
        if (!$session->matches($refreshtoken)) {
            throw new InvalidRefreshToken($refreshtoken);
        }
        $newToken = bin2hex(random_bytes(32));
        $session->rotate($newToken);

        $accessToken = $this->tokenService->generateToken(
            $this->userRepository->findById($session->userId)
        );
        $this->sessionRepository->save($session);
        return new RefreshTokenResponse($accessToken, $newToken);
    }
}