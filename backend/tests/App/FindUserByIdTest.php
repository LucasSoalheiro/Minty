<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\UserResponseDto;
use Src\App\Error\UserNotFound;
use Src\App\Usecases\FindUserByIdUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeUserRepository;

class FindUserByIdTest extends TestCase
{
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser(): User
    {
        $user = User::create("John Doe", Email::create("john.doe@example.com"), Password::create("P@ssw0rd"));
        $this->userRepository->save($user);
        return $user;

    }

    public function testFindByIdUsecase(): void
    {
        $usecase = new FindUserByIdUsecase($this->userRepository);
        $userCreated = $this->makeUser();
        $user = $usecase->execute($userCreated->id);

        $this->assertInstanceOf(UserResponseDto::class, $user);
    }

    public function testFindByIdUsecaseWithNonExistingId(): void
    {
        $usecase = new FindUserByIdUsecase($this->userRepository);
        $this->expectException(UserNotFound::class);
        $usecase->execute(999);
    }

}