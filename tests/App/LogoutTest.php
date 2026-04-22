<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\LoginDto;
use Src\App\Security\TokenService;
use Src\App\Usecases\LoginUsecase;
use Src\App\Usecases\LogoutUsecase;
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

class LogoutTest extends TestCase
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

    private function makeUser() 
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
    }

    private function makeLogin()
    {
        $this->makeUser();
        $authenticate = new LoginUsecase($this->sessionRepository, $this->userRepository,$this->tokenService);
        $dto = new LoginDto('john.doe@example.com', 'P@ssw0rd');
        return $authenticate->execute($dto);
    }

    public function testLogout(): void 
    {
        $tokens = $this->makeLogin();
        $logout = new LogoutUsecase($this->sessionRepository);
        $logout->execute($tokens->refreshToken);
        $token = $this->sessionRepository->findByToken($tokens->refreshToken);
        $this->assertEquals(true, $token->revoked);
    }
}