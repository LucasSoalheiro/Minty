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
        if (!$entity) {
            $entity = SessionMapper::toPersistence($data);
        } else {
            SessionMapper::updatePersistence($entity, $data);
        }
        $this->em->persist($entity);
        $this->em->flush();
    }

    #[Override]
    public function findByToken(string $token): ?Session
    {
        $tokenHash = hash('sha256', $token);
        /**
         * @var SessionEntity|null
         */
        $session = $this->repo->findOneBy(["tokenHash" => $tokenHash]);
        
        if (!$session) {
            return null;
        }

        return SessionMapper::toDomain(
            $session->getId(),
            $session->getUserId(),
            $session->getTokenHash(),
            $session->getExpiresAt(),
            $session->getRevoked()
        );
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