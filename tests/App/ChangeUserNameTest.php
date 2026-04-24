<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\ChangeUserNameDto;
use Src\App\Usecases\ChangeUserNameUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\Error\NameShouldBeDifferent;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeUserRepository;

class ChangeUserNameTest extends TestCase
{
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser(): User
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }

    public function testChangeUserName(): void
    {
        $user = $this->makeUser();
        $dto = new ChangeUserNameDto("Jane Doe", $user->email->__toString());
        $changeUserNameUsecase = new ChangeUserNameUsecase($this->userRepository);
        $changeUserNameUsecase->execute($dto);
        $updatedUser = $this->userRepository->findByEmail($user->email->__toString());
        $this->assertEquals("Jane Doe", $updatedUser->name);
    }
    public function testChangeUserNameWithTheSameName(): void
    {
        $user = $this->makeUser();
        $dto = new ChangeUserNameDto("John Doe", $user->email->__toString());
        $changeUserNameUsecase = new ChangeUserNameUsecase($this->userRepository);
        $this->expectException(NameShouldBeDifferent::class);
        $changeUserNameUsecase->execute($dto);
    }

    public function testChangeUserNameWithNullName():void
    {
        $user = $this->makeUser();
        $dto = new ChangeUserNameDto("", $user->email->__toString());
        $changeUserNameUsecase = new ChangeUserNameUsecase($this->userRepository);
        $this->expectException(NameCannotBeNull::class);
        $changeUserNameUsecase->execute($dto);
    }

}