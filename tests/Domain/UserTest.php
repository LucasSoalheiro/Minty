<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use Src\Domain\Error\EmailShouldBeDifferent;
use Src\Domain\Error\InvalidEmail;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\Error\NameShouldBeDifferent;
use Src\Domain\Error\WeakPassword;
use Src\Domain\User\User;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;

class UserTest extends TestCase
{

    public function testShouldCreateUser(): void
    {
        $email = Email::create("test@example.com");
        $password = Password::restore("P@ssw0rd");

        $user = User::create("John Doe", $email, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("John Doe", $user->getName());
        $this->assertTrue($user->getEmail()->equals($email));
        $this->assertInstanceOf(Password::class, $user->getPassword());
    }

    public function testShouldNotCreateUserWithInvalidEmail(): void
    {
        $this->expectException(InvalidEmail::class);

        $email = Email::create("invalid-email");
        $password = Password::restore("P@ssw0rd");

        User::create("John Doe", $email, $password);
    }

    public function testShouldNotCreateUserWithWeakPassword(): void
    {
        $this->expectException(WeakPassword::class);

        $email = Email::create("test@example.com");
        $password = "weak";
        Password::validate($password);
        $passwordRestored = Password::restore($password);

        User::create("John Doe", $email, $passwordRestored);
    }

    public function testShouldNotCreateUserWithEmptyName(): void
    {
        $this->expectException(NameCannotBeNull::class);

        $email = Email::create("test@example.com");
        $password = Password::restore("P@ssw0rd");

        User::create("", $email, $password);
    }

    public function testShouldChangeEmail(): void
    {
        $user = $this->makeUser();

        $newEmail = Email::create("new@example.com");

        $user->changeEmail($newEmail);

        $this->assertTrue($user->getEmail()->equals($newEmail));
    }

    public function testShouldNotChangeToSameEmail(): void
    {
        $this->expectException(EmailShouldBeDifferent::class);

        $user = $this->makeUser();

        $user->changeEmail($user->getEmail());
    }

    public function testShouldChangeName(): void
    {
        $user = $this->makeUser();

        $user->changeName("Jane Doe");

        $this->assertEquals("Jane Doe", $user->getName());
    }

    public function testShouldNotChangeNameToSame(): void
    {
        $this->expectException(NameShouldBeDifferent::class);

        $user = $this->makeUser();

        $user->changeName("John Doe");
    }

    public function testShouldChangePassword(): void
    {
        $user = $this->makeUser();

        $user->changePassword("NewP@ssw0rd");

        $this->assertEquals("NewP@ssw0rd", $user->getPassword()->value());
    }

    public function testShouldNotChangePasswordToWeakOne(): void
    {
        $this->expectException(WeakPassword::class);

        $user = $this->makeUser();

        $user->changePassword("weak");
    }

    private function makeUser(): User
    {
        $email = Email::create("test@example.com");
        $password = Password::restore("P@ssw0rd");

        return User::create("John Doe", $email, $password);
    }
}