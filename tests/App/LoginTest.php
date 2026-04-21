<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\LoginDto;
use Src\App\DTO\LoginResponseDto;
use Src\App\Security\TokenService;
use Src\App\Usecases\LoginUsecase;
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

class LoginTest extends TestCase
{
    private UserRepository $userRepository;
    private Hasher $passwordHasher;
    private TokenService $tokenService;
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $this->sessionRepository = new FakeSessionRepository();
        $this->userRepository = new FakeUserRepository();
        $this->passwordHasher = new FakeHasher();
        $this->tokenService = new FakeTokenService();
    }

    private function makeUser(): User
    {
        Password::validate('P@ssw0rd');
        $passwordHash = $this->passwordHasher->hash('P@ssw0rd');
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::restore($passwordHash)));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }

    public function testLogin(): void
    {
        $user = $this->makeUser();
        $authenticate = new LoginUsecase($this->sessionRepository,$this->userRepository, $this->passwordHasher, $this->tokenService);
        $dto = new LoginDto('john.doe@example.com', 'P@ssw0rd');
        $response = $authenticate->execute($dto);
        $tokenPayload = $this->tokenService->validateToken("fake-token-{$user->id->__toString()}");
        $this->assertInstanceOf(LoginResponseDto::class, $response);
        $this->assertNotNull($tokenPayload);
        $this->assertEquals($user->id->__toString(), $tokenPayload->userId);
        $this->assertEquals($user->email->__toString(), $tokenPayload->claims['email']);
    }

    public function testAuthenticateWithWrongPassword(): void
    {
        $this->makeUser();
        $authenticate = new LoginUsecase($this->sessionRepository,$this->userRepository, $this->passwordHasher, $this->tokenService);
        $dto = new LoginDto('john.doe@example.com', 'WrongPassword');
        $this->expectException(\Src\App\Error\WrongPassword::class);
        $authenticate->execute($dto);
    }

    public function testAuthenticateWithNonExistingEmail(): void
    {
        $authenticate = new LoginUsecase($this->sessionRepository,$this->userRepository, $this->passwordHasher, $this->tokenService);
        $dto = new LoginDto('non.existing@example.com', 'P@ssw0rd');
        $this->expectException(\Src\App\Error\EmailNotFound::class);
        $authenticate->execute($dto);
    }

}