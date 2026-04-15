<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\AuthenticateDto;
use Src\App\Security\TokenService;
use Src\App\Usecases\AuthenticateUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeHasher;
use Tests\fake\FakeTokenService;
use Tests\fake\FakeUserRepository;

class AuthenticateTest extends TestCase
{
    private UserRepository $userRepository;
    private Hasher $passwordHasher;
    private TokenService $tokenService;
    public function setUp(): void
    {
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

    public function testAuthenticate(): void
    {
        $user = $this->makeUser();
        $authenticate = new AuthenticateUsecase($this->userRepository, $this->passwordHasher, $this->tokenService);
        $dto = new AuthenticateDto('john.doe@example.com', 'P@ssw0rd');
        $authenticate->execute($dto);
        $tokenPayload = $this->tokenService->validateToken("fake-token-{$user->getId()->__toString()}");
        $this->assertNotNull($tokenPayload);
        $this->assertEquals($user->getId()->__toString(), $tokenPayload->userId);
        $this->assertEquals($user->getEmail()->__toString(), $tokenPayload->claims['email']);
    }

    public function testAuthenticateWithWrongPassword(): void
    {
        $this->makeUser();
        $authenticate = new AuthenticateUsecase($this->userRepository, $this->passwordHasher, $this->tokenService);
        $dto = new AuthenticateDto('john.doe@example.com', 'WrongPassword');
        $this->expectException(\Src\App\Error\WrongPassword::class);
        $authenticate->execute($dto);
    }

    public function testAuthenticateWithNonExistingEmail(): void
    {
        $authenticate = new AuthenticateUsecase($this->userRepository, $this->passwordHasher, $this->tokenService);
        $dto = new AuthenticateDto('non.existing@example.com', 'P@ssw0rd');
        $this->expectException(\Src\App\Error\EmailNotFound::class);
        $authenticate->execute($dto);
    }

}