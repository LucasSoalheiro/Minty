<?php

namespace Src\Infra\Db\Mapper;

use Src\Domain\Entities\User;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\UserEntity;

class UserMapper
{
    public static function toDomain(
        string $id,
        string $name,
        string $email,
        string $password
    ): User {
        return User::restore(
            UUID::fromString($id),
            $name,
            Email::restore($email),
            Password::restore($password)
        );
    }

    public static function toPersistence(User $domain): UserEntity
    {
        $entity = new UserEntity();
        $entity->setId($domain->id->__toString());
        $entity->setName($domain->name);
        $entity->setEmail($domain->email->__toString());
        $entity->setPassword($domain->password->__toString());
        return $entity;
    }

    public static function updatePersistence(UserEntity $entity, User $domain): void
    {
        $entity->setName($domain->name);
        $entity->setEmail($domain->email->__toString());
        $entity->setPassword($domain->password->__toString());
    }
}