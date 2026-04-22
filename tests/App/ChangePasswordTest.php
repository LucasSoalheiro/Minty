<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\ChangePasswordDto;
use Src\App\Error\WrongPassword;
use Src\App\Usecases\ChangePasswordUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeHasher;
use Tests\fake\FakeUserRepository;


class ChangePasswordTest extends TestCase
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

    public function testChangePassword(): void
    {
        $user = $this->makeUser();
        $dto = new ChangePasswordDto($user->email->__toString(), 'P@ssw0rd', 'NewP@ssw0rd');
        $changePasswordUsecase = new ChangePasswordUsecase($this->userRepository);
        $changePasswordUsecase->execute($dto);
        $updatedUser = $this->userRepository->findByEmail($user->email->__toString());
        $this->assertTrue($user->passwordMatch('NewP@ssw0rd'));
    }
}