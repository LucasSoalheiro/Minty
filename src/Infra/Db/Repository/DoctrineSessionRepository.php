<?php

namespace Src\Infra\Db\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Override;
use Doctrine\ORM\EntityRepository;
use Src\Domain\Entities\Session;
use Src\Domain\Repository\SessionRepository;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\SessionEntity;
use Src\Infra\Db\Mapper\SessionMapper;

class DoctrineSessionRepository implements SessionRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(SessionEntity::class);
    }
    #[Override]
    public function save(Session $data): void
    {
        $entity = $this->repo->find($data->id->__toString());
        !$entity ?
            $entity = SessionMapper::toPersistence($data) : SessionMapper::updatePersistence($entity, $data);
        $this->em->persist($entity);
        $this->em->flush();
    }

    #[Override]
    public function findByToken(string $token): ?Session
    {
        /**
         * @var SessionEntity
         */
        $session = $this->repo->findOneBy(["tokenHash" => $token]);
        return SessionMapper::toDomain(
            $session->getId(),
            $session->getUserId(),
            $session->getTokenHash(),
            $session->getExpiresAt(),
            $session->getRevoked()
        ) ?? null;
    }

    #[Override]
    public function findByUserId(UUID $userId): array
    {
        $sessions = $this->repo->findBy(["userId" => $userId->__toString()]);
        return array_map(fn(SessionEntity $session) => SessionMapper::toDomain(
            $session->getId(),
            $session->getUserId(),
            $session->getTokenHash(),
            $session->getExpiresAt(),
            $session->getRevoked()
        ), $sessions);
    }
}