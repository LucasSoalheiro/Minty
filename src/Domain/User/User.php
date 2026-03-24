<?php
namespace Src\Domain\User;

use Src\Domain\User\VO\Email;
use Src\Domain\User\VO\Password;
use Src\Domain\Util\UUID;

class User
{
    private readonly UUID $uuid = UUID::generate();

    private function __construct(
        private string $name,
        private Email $email,
        private Password $password
    ) {
    }

    public static function create(string $name, Email $email, Password $password): User
    {
        return new User($name, $email, $password);
    }

    public static function restore(string $name, Email $email, Password $password): User
    {
        return new User($name, $email, $password);
    }
    public function getUuid(): string
    {
        return $this->uuid->__toString();
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email->__toString();
    }

    public function getPassword(): string
    {
        return $this->password->__toString();
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changeEmail(Email $email): void
    {
        if ($this->email->__toString() === $email->__toString()) {
            throw new \ErrorException("Email is already in use.");
        }
        $this->email = $email;
    }

    public function changePassword(Password $newPassword): void
    {   
        $this->password = $newPassword;
    }

}