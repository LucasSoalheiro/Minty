<?php

namespace Src\Infra\Db\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Override;
use Src\Domain\Entities\Account;
use Src\Domain\Repository\AccountRepository;
use Src\Infra\Db\Entity\AccountEntity;
use Src\Infra\Db\Mapper\AccountMapper;

class DoctrineAccountRepository implements AccountRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(AccountEntity::class);
    }

    #[Override]
    public function save(Account $account): void
    {
        $entity = $this->repo->find($account->id->__toString());
        if (!$entity) {
            $entity = AccountMapper::toPersistence($account);
        } else {
            AccountMapper::updatePersistence($entity, $account);
        }
        $this->em->persist($entity);
        $this->em->flush();
    }

    #[Override]
    public function findById(string $id): ?Account
    {
        /** @var AccountEntity|null $entity */
        $entity = $this->repo->find($id);
        if (!$entity) {
            return null;
        }

        return AccountMapper::toDomain(
            $entity->getId(),
            $entity->getName(),
            $entity->getBalance(),
            $entity->getUserId(),
            $entity->getIsActive()
        );
    }

    #[Override]
    public function list(string $userId): array
    {
        $entities = $this->repo->findBy(['userId' => $userId]);

        return array_map(
            fn(AccountEntity $entity) => AccountMapper::toDomain(
                $entity->getId(),
                $entity->getName(),
                $entity->getBalance(),
                $entity->getUserId(),
                $entity->getIsActive()
            ),
            $entities
        );
    }
}
