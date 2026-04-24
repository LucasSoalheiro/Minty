<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\CreateUserDto;
use Src\App\Error\EmailAlreadyInUse;
use Src\App\Usecases\CreateUserUsecase;
use Src\Domain\Repository\UserRepository;
use Tests\fake\FakeUserRepository;

class CreateUserTest extends TestCase
{
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
        
    }
    private function makeUser(): CreateUserDto
    {
        return new CreateUserDto("John Doe", "john.doe@example.com", "P@ssw0rd");
    }

    public function testCreateUserUsecase(): void
    {
        $usecase = new CreateUserUsecase($this->userRepository);
        $dto = $this->makeUser();
        $usecase->execute($dto);
        $user = $this->userRepository->findByEmail("john.doe@example.com");
        $this->assertNotNull($user);
    }

    public function testCreateUserWithExistingEmail(): void
    {
        $usecase = new CreateUserUsecase($this->userRepository);
        $dto = $this->makeUser();
        $usecase->execute($dto);
        $this->expectException(EmailAlreadyInUse::class);
        $usecase->execute($dto);
    }

}