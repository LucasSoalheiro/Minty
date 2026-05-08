<?php

namespace Src\Infra\Db\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Override;
use Src\Domain\Entities\Category;
use Src\Domain\Repository\CategoryRepository;
use Src\Infra\Db\Entity\CategoryEntity;
use Src\Infra\Db\Mapper\CategoryMapper;

class DoctrineCategoryRepository implements CategoryRepository
{
    private EntityRepository $repo;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(CategoryEntity::class);
    }

    #[Override]
    public function save(Category $category): void
    {
        $entity = $this->repo->find($category->id->__toString());
        if (!$entity) {
            $entity = CategoryMapper::toPersistence($category);
        } else {
            CategoryMapper::updatePersistence($entity, $category);
        }
        $this->em->persist($entity);
        $this->em->flush();
    }

    #[Override]
    public function findById(string $id): ?Category
    {
        /** @var CategoryEntity|null $entity */
        $entity = $this->repo->find($id);
        if (!$entity) {
            return null;
        }

        return CategoryMapper::toDomain(
            $entity->getId(),
            $entity->getName(),
            $entity->getDescription(),
            $entity->getUserId(),
            $entity->getIsActive()
        );
    }

    #[Override]
    public function findAllByUserId(string $userId, ?bool $isActive = true): array
    {
        $criteria = ['userId' => $userId];
        if ($isActive !== null) {
            $criteria['isActive'] = $isActive;
        }

        $entities = $this->repo->findBy($criteria);

        return array_map(
            fn(CategoryEntity $entity) => CategoryMapper::toDomain(
                $entity->getId(),
                $entity->getName(),
                $entity->getDescription(),
                $entity->getUserId(),
                $entity->getIsActive()
            ),
            $entities
        );
    }
}
