<?php

namespace Tests\Infra;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\LoginDto;
use Src\App\DTO\LoginResponseDto;
use Src\App\Security\TokenService;
use Src\App\Usecases\LoginUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Src\Infra\Http\Security\JWT;
use Tests\fake\FakeSessionRepository;
use Tests\fake\FakeUserRepository;

class JWTTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->sessionRepository = new FakeSessionRepository();
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser(): User
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }

    public function testJwt(): void
    {
        $token = "Secret_made_only_for_tests_so_you_can_ignore_it";
        $tokenService = new JWT($token);
        $user = $this->makeUser();
        $authenticate = new LoginUsecase($this->sessionRepository, $this->userRepository, $tokenService);
        $dto = new LoginDto('john.doe@example.com', 'P@ssw0rd');
        $response = $authenticate->execute($dto);
        $tokenPayload = $tokenService->validateToken($response->accessToken);
        $this->assertInstanceOf(LoginResponseDto::class, $response);
        $this->assertNotNull($tokenPayload);
        $this->assertEquals($user->id, $tokenPayload->userId);
    }
}