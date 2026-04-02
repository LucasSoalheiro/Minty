<?php
namespace Src\Domain\User;

use Src\Domain\Error\EmailShouldBeDifferent;
use Src\Domain\Error\InvalidPassword;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\Error\NameShouldBeDifferent;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\UUID;

class User
{

    private function __construct(
        private readonly UUID $id,
        private string $name,
        private Email $email,
        private Password $password
    ) {

    }

    public static function create(string $name, Email $email, Password $password): self
    {

        if (empty($name)) {
            throw new NameCannotBeNull();
        }
        return new self(UUID::generate(), $name, $email, $password);
    }

    public static function restore(UUID $id, string $name, Email $email, Password $password): self
    {
        return new self($id, $name, $email, $password);
    }
    public function getId(): UUID
    {
        return $this->id;
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
        string $newPassword,
    ): void {
        $this->password = Password::restore($newPassword);
    }

}