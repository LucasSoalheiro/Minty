<?php

namespace Src\Infra\Db\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Override;
use Src\Domain\Entities\Transaction;
use Src\Domain\Entities\TransactionStatusEnum;
use Src\Domain\Repository\TransactionRepository;
use Src\Infra\Db\Entity\TransactionEntity;
use Src\Infra\Db\Mapper\TransactionMapper;

class DoctrineTransactionRepository implements TransactionRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(TransactionEntity::class);
    }

    #[Override]
    public function save(Transaction $transaction): void
    {
        $entity = $this->repo->find($transaction->id->__toString());
        if (!$entity) {
            $entity = TransactionMapper::toPersistence($transaction);
        } else {
            TransactionMapper::updatePersistence($entity, $transaction);
        }
        $this->em->persist($entity);
        $this->em->flush();
    }

    #[Override]
    public function list(string $accountId, ?TransactionStatusEnum $status = null): array
    {
        $criteria = ['accountId' => $accountId];
        if ($status !== null) {
            $criteria['status'] = $status->name;
        }

        $entities = $this->repo->findBy($criteria);

        return array_map(
            fn(TransactionEntity $entity) => TransactionMapper::toDomain(
                $entity->getId(),
                $entity->getAccountId(),
                $entity->getAmount(),
                $entity->getCreatedAt(),
                $entity->getType(),
                $entity->getStatus(),
                $entity->getDescription(),
                $entity->getCategoryId()
            ),
            $entities
        );
    }

    #[Override]
    public function findById(string $id): ?Transaction
    {
        /** @var TransactionEntity|null $entity */
        $entity = $this->repo->find($id);
        if (!$entity) {
            return null;
        }

        return TransactionMapper::toDomain(
            $entity->getId(),
            $entity->getAccountId(),
            $entity->getAmount(),
            $entity->getCreatedAt(),
            $entity->getType(),
            $entity->getStatus(),
            $entity->getDescription(),
            $entity->getCategoryId()
        );
    }

    #[Override]
    public function findByAccountId(string $accountId): array
    {
        $entities = $this->repo->findBy(['accountId' => $accountId]);

        return array_map(
            fn(TransactionEntity $entity) => TransactionMapper::toDomain(
                $entity->getId(),
                $entity->getAccountId(),
                $entity->getAmount(),
                $entity->getCreatedAt(),
                $entity->getType(),
                $entity->getStatus(),
                $entity->getDescription(),
                $entity->getCategoryId()
            ),
            $entities
        );
    }
}
