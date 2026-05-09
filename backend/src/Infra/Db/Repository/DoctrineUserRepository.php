<?php

namespace Src\Infra\Db\Repository;

use Override;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Src\Domain\Entities\User;
use Src\Domain\Repository\UserRepository;
use Src\Infra\Db\Entity\UserEntity;
use Src\Infra\Db\Mapper\UserMapper;

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
        $entity = $this->repo->find($user->id->__toString());
        if (!$entity) {
            $entity = UserMapper::toPersistence($user);
        } else {
            UserMapper::updatePersistence($entity, $user);
        }
        $this->em->persist($entity);
        $this->em->flush();
    }

    #[Override]
    public function findByEmail(string $email): ?User
    {
        /**
         * @var UserEntity|null
         */
        $user = $this->repo->findOneBy(["email" => $email]);
        
        if (!$user) {
            return null;
        }

        return UserMapper::toDomain(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getPassword()
        );
    }

    #[Override]
    public function searchByEmail(string $email): array
    {
        $qb = $this->repo->createQueryBuilder('u');

        $entities = $qb
            ->where('u.email LIKE :email')
            ->setParameter('email', '%' . $email . '%')
            ->getQuery()
            ->getResult();

        return array_map(
            fn(UserEntity $e) => UserMapper::toDomain(
                $e->getId(),
                $e->getName(),
                $e->getEmail(),
                $e->getPassword()
            ),
            $entities
        );
    }

    #[Override]
    public function findById(string $id): ?User
    {
        /**
         * @var UserEntity|null
         */
        $user = $this->repo->find($id);

        if (!$user) {
            return null;
        }

        return UserMapper::toDomain(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getPassword()
        );
    }

}