<?php
namespace Src\Domain\Entities;

use Src\Domain\Error\EmailShouldBeDifferent;
use Src\Domain\Error\NameCannotBeNull;
use Src\Domain\Error\NameShouldBeDifferent;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\UUID;

final class User
{
    private function __construct(
        public readonly UUID $id,
        public private(set) string $name {
            set(string $name) {
                if(empty($name)) {
                    throw new NameCannotBeNull();
                }
                $this->name = $name;
            }
            get => $this->name;
        },
        public private(set) Email $email,
        public private(set) Password $password
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
    public function changeName(string $name): void
    {
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
        string $newPassword
    ): void {

        $this->password = Password::restore($newPassword);
    }

}