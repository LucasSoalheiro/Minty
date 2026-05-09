<?php

namespace Src\Infra\Db\Mapper;

use DateTime;
use DateTimeImmutable;
use Src\Domain\Entities\Session;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\SessionEntity;

class SessionMapper
{
    public static function toDomain(
        string $id,
        string $userId,
        string $tokenHash,
        DateTime $expiresAt,
        bool $revoked
    ): Session {
        return Session::restore(
            UUID::fromString($id),
            UUID::fromString($userId),
            $tokenHash,
            DateTimeImmutable::createFromMutable($expiresAt),
            $revoked
        );
    }

    public static function toPersistence(Session $domain): SessionEntity
    {
        $entity = new SessionEntity();
        $entity->setId($domain->id->__toString());
        $entity->setTokenHash($domain->tokenHash);
        $entity->setUserId($domain->userId->__toString());
        $entity->setExpiresAt(DateTime::createFromImmutable($domain->expiresAt));
        $entity->setRevoked($domain->revoked);
        return $entity;
    }

    public static function updatePersistence(SessionEntity $entity, Session $domain): void
    {
        $entity->setTokenHash($domain->tokenHash);
        $entity->setExpiresAt(DateTime::createFromImmutable($domain->expiresAt));
        $entity->setRevoked($domain->revoked);
    }
}