<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\ChangePasswordDto;
use Src\App\Error\WrongPassword;
use Src\App\Usecases\ChangePasswordUsecase;
use Src\Domain\User\PasswordHasher;
use Src\Domain\User\User;
use Src\Domain\User\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeHasher;
use Tests\fake\FakeUserRepository;


class ChangePasswordTest extends TestCase
{
    private UserRepository $userRepository;
    private PasswordHasher $passwordHasher;
    public function setUp(): void
    {
        $this->userRepository = new FakeUserRepository();
        $this->passwordHasher = new FakeHasher();
    }

    private function makeUser(): User
    {
        Password::validate('P@ssw0rd');
        $passwordHash = $this->passwordHasher->hash('P@ssw0rd');
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::restore($passwordHash)));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }

    public function testChangePassword(): void
    {
        $user = $this->makeUser();
        $dto = new ChangePasswordDto($user->getEmail()->__toString(), 'P@ssw0rd', 'NewP@ssw0rd');
        $changePasswordUsecase = new ChangePasswordUsecase($this->userRepository, $this->passwordHasher);
        $changePasswordUsecase->execute($dto);
        $updatedUser = $this->userRepository->findByEmail($user->getEmail()->__toString());
        $this->assertTrue($this->passwordHasher->compare('NewP@ssw0rd', $updatedUser->getPassword()->value()));
    }

    public function testChangePasswordWithWrongPassword(): void
    {
        $user = $this->makeUser();
        $dto = new ChangePasswordDto($user->getEmail()->__toString(), 'wrong_password', 'NewP@ssw0rd');
        $changePasswordUsecase = new ChangePasswordUsecase($this->userRepository, $this->passwordHasher);
        $this->expectException(WrongPassword::class);
        $changePasswordUsecase->execute($dto);

    }
}