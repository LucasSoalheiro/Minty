<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\LoginDto;
use Src\App\Security\TokenService;
use Src\App\Usecases\LoginUsecase;
use Src\App\Usecases\RefreshTokenUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeHasher;
use Tests\fake\FakeSessionRepository;
use Tests\fake\FakeTokenService;
use Tests\fake\FakeUserRepository;

class RefreshTokenTest extends TestCase
{
    private UserRepository $userRepository;
    private TokenService $tokenService;
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $this->sessionRepository = new FakeSessionRepository();
        $this->userRepository = new FakeUserRepository();
        $this->tokenService = new FakeTokenService();
    }

    private function makeLogin()
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
        $authenticate = new LoginUsecase($this->sessionRepository, $this->userRepository, $this->tokenService);
        $dto = new LoginDto('john.doe@example.com', 'P@ssw0rd');
        return $authenticate->execute($dto);
    }

    public function testRefreshToken(): void
    {
        $tokens = $this->makeLogin();
        $refreshTokenUsecase = new RefreshTokenUsecase($this->sessionRepository, $this->tokenService, $this->userRepository);
        $newTokens = $refreshTokenUsecase->execute($tokens->refreshToken);
        $this->assertNotEquals($tokens->refreshToken, $newTokens->refreshToken);
    }

}