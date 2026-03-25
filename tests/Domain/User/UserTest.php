<?php

namespace Tests\Domain\User;

use PHPUnit\Framework\TestCase;
use Src\Domain\User\User;
use Src\Domain\User\vo\Email;
use Src\Domain\User\vo\Password;
use Src\Domain\User\error\InvalidEmail;
use Src\Domain\User\error\WeakPassword;
use Src\Domain\User\error\InvalidPassword;
use Src\Domain\User\error\EmailShouldBeDifferent;
use Src\Domain\User\error\NameCannotBeNull;
use Src\Domain\User\error\NameShouldBeDifferent;
use Tests\Domain\User\fake\FakeHasher;

class UserTest extends TestCase
{
    private FakeHasher $hasher;

    protected function setUp(): void
    {
        $this->hasher = new FakeHasher();
    }

    public function testShouldCreateUser(): void
    {
        $email = Email::create("test@example.com");
        $password = Password::create("P@ssw0rd", $this->hasher);

        $user = User::create("John Doe", $email, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals("John Doe", $user->getName());
        $this->assertTrue($user->getEmail()->equals($email));
        $this->assertTrue($user->getPassword()->equals($password));
    }

    public function testShouldNotCreateUserWithInvalidEmail(): void
    {
        $this->expectException(InvalidEmail::class);

        $email = Email::create("invalid-email");
        $password = Password::create("P@ssw0rd", $this->hasher);

        User::create("John Doe", $email, $password);
    }

    public function testShouldNotCreateUserWithWeakPassword(): void
    {
        $this->expectException(WeakPassword::class);

        $email = Email::create("test@example.com");
        $password = Password::create("weak", $this->hasher);

        User::create("John Doe", $email, $password);
    }

    public function testShouldNotCreateUserWithEmptyName(): void
    {
        $this->expectException(NameCannotBeNull::class);

        $email = Email::create("test@example.com");
        $password = Password::create("P@ssw0rd", $this->hasher);

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

        $user->changePassword("P@ssw0rd", "NewP@ssw0rd", $this->hasher);

        $this->assertTrue(
            $user->getPassword()->verify("NewP@ssw0rd", $this->hasher)
        );
    }

    public function testShouldNotChangePasswordWithWrongCurrentPassword(): void
    {
        $this->expectException(InvalidPassword::class);

        $user = $this->makeUser();

        $user->changePassword("wrong", "NewP@ssw0rd", $this->hasher);
    }

    public function testShouldNotChangePasswordToWeakOne(): void
    {
        $this->expectException(WeakPassword::class);

        $user = $this->makeUser();

        $user->changePassword("P@ssw0rd", "weak", $this->hasher);
    }

    private function makeUser(): User
    {
        $email = Email::create("test@example.com");
        $password = Password::create("P@ssw0rd", $this->hasher);

        return User::create("John Doe", $email, $password);
    }
}