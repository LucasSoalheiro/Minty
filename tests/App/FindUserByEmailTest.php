<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\UserResponseDto;
use Src\App\Error\EmailNotFound;
use Src\App\Usecases\FindByEmailUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeUserRepository;

class FindUserByEmailTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser(): void
    {
        $user = User::create("John Doe", Email::create("john.doe@example.com"), Password::restore("P@ssw0rd"));
        $this->userRepository->save($user);
    }


    public function testFindByEmailUsecase(): void
    {
        $usecase = new FindByEmailUsecase($this->userRepository);
        $this->makeUser();
        $user = $usecase->execute("john.doe@example.com");

        $this->assertInstanceOf(UserResponseDto::class, $user);
    }

    public function testFindByEmailUsecaseWithNonExistingEmail(): void
    {
        $usecase = new FindByEmailUsecase($this->userRepository);
        $this->expectException(EmailNotFound::class);
        $usecase->execute("non.existing@example.com");
    }
}