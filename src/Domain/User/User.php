<?php
namespace Src\Domain\User;

use Src\Domain\User\error\EmailShouldBeDifferent;
use Src\Domain\User\error\InvalidPassword;
use Src\Domain\User\error\NameCannotBeNull;
use Src\Domain\User\error\NameShouldBeDifferent;
use Src\Domain\User\Repository\PasswordHasher;
use Src\Domain\User\vo\Email;
use Src\Domain\User\vo\Password;
use Src\Domain\shared\UUID;

class User
{
    private readonly UUID $id;

    private function __construct(
        private string $name,
        private Email $email,
        private Password $password
    ) {
        $this->id = UUID::generate();
    }

    public static function create(string $name, Email $email, Password $password): self
    {

        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new self($name, $email, $password);
    }

    public static function restore(string $name, Email $email, Password $password): self
    {
        return new self($name, $email, $password);
    }
    public function getId(): string
    {
        return $this->id->__toString();
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function changeName(string $name): void
    {
        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        if ($this->name === $name) {
            throw new NameShouldBeDifferent($name);
        }
        $this->name = $name;
    }

    public function changeEmail(Email $email): void
    {
        if ($this->email->equals($email)) {
            throw new EmailShouldBeDifferent($email->__toString());
        }
        $this->email = $email;
    }

    public function changePassword(
        string $currentPassword,
        string $newPassword,
        PasswordHasher $hasher
    ): void {
        if (!$this->password->verify($currentPassword, $hasher)) {
            throw new InvalidPassword();
        }

        $this->password = Password::create($newPassword, $hasher);
    }

}