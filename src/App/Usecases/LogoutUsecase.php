<?php

namespace Src\App\Usecases;

use Src\App\Error\SessionNotFound;
use Src\Domain\Repository\SessionRepository;

class LogoutUsecase
{
    public function __construct(
        private readonly SessionRepository $sessionRepository
    ) {
    }

    public function execute(string $refreshToken)
    {
        $session = $this->sessionRepository->findByToken($refreshToken);

        if (!$session){
            throw new SessionNotFound($refreshToken);
        }

        $session->revoke();

        $this->sessionRepository->save($session);
    }
}