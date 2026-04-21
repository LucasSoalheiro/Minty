<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\ChangeEmailDto;
use Src\App\Error\EmailAlreadyInUse;
use Src\App\Error\WrongPassword;
use Src\App\Usecases\ChangeEmailUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\Hasher;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeHasher;
use Tests\fake\FakeUserRepository;

class ChangeEmailTest extends TestCase
{
    private UserRepository $userRepository;
    private Hasher $passwordHasher;
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


    public function testChangeEmail(): void
    {
        $user = $this->makeUser();
        $dto = new ChangeEmailDto($user->id->__toString(), 'jane.doe@example.com', 'P@ssw0rd');
        $changeEmailUsecase = new ChangeEmailUsecase($this->userRepository, $this->passwordHasher);

        $changeEmailUsecase->execute($dto);
        $updatedUser = $this->userRepository->findById($user->id);
        $this->assertEquals('jane.doe@example.com', $updatedUser->email->__toString());
    }

    public function testChangeEmailWithWrongPassword(): void
    {
        $user = $this->makeUser();
        $dto = new ChangeEmailDto($user->id->__toString(), 'jane.doe@example.com', 'WrongPassword');
        $changeEmailUsecase = new ChangeEmailUsecase($this->userRepository, $this->passwordHasher);

        $this->expectException(WrongPassword::class);
        $changeEmailUsecase->execute($dto);
    }

    public function testChangeEmailToExistingEmail(): void
    {
        $user1 = $this->makeUser();
        $user2 = User::create('Jane Doe', Email::create('jane.doe@example.com'), Password::restore($this->passwordHasher->hash('P@ssw0rd')));
        $this->userRepository->save($user2);

        $dto = new ChangeEmailDto($user1->id->__toString(), 'jane.doe@example.com', 'P@ssw0rd');
        $changeEmailUsecase = new ChangeEmailUsecase($this->userRepository, $this->passwordHasher);
        $this->expectException(EmailAlreadyInUse::class);
        $changeEmailUsecase->execute($dto);
    }
}