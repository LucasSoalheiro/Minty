<?php

namespace Src\Infra\Db\Mapper;

use Src\Domain\Entities\User;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\UserEntity;

class UserMapper
{
    public static function toDomain(string $id, string $name, string $email, string $password): User
    {
        return User::restore(
            UUID::fromString($id),
            $name,
            Email::restore($email),
            Password::create($password)
        );
    }

    public static function toPersistence(User $user)
    {
        $entity = new UserEntity();
        $entity->setId($user->id->__toString());
        $entity->setName($user->name);
        $entity->setEmail($user->email->__toString());
        $entity->setPassword($user->password->__toString());
        return $entity;
    }

    public static function updatePersistence(UserEntity $entity, User $user): void
    {
        $entity->setName($user->name);
        $entity->setEmail($user->email->__toString());
        $entity->setPassword($user->password->__toString());
    }
}