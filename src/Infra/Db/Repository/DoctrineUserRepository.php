<?php

namespace Src\Infra\Db\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Domain\Entities\User;
use Override;
use Src\Domain\Repository\UserRepository;
use Src\Infra\Db\Entity\UserEntity;

class DoctrineUserRepository implements UserRepository
{
    private EntityRepository $repo;
    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(UserEntity::class);
    }

    #[Override]
    public function save(User $user): void
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function findByEmail(string $email): ?User
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function searchByEmail(string $email): array
    {
        throw new \Exception('Not implemented');
    }

    #[Override]
    public function findById(string $id): ?User
    {
        throw new \Exception('Not implemented');
    }

}