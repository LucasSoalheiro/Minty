<?php

namespace Src\Infra\Db\Mapper;

use Src\Domain\Entities\Category;
use Src\Domain\ValueObject\UUID;
use Src\Infra\Db\Entity\CategoryEntity;

class CategoryMapper
{
    public static function toDomain(
        string $id,
        string $name,
        ?string $description,
        string $userId,
        bool $isActive
    ): Category {
        return Category::restore(
            UUID::fromString($id),
            $name,
            $description,
            UUID::fromString($userId),
            $isActive
        );
    }

    public static function toPersistence(Category $domain): CategoryEntity
    {
        $entity = new CategoryEntity();
        $entity->setId($domain->id->__toString());
        $entity->setName($domain->name);
        $entity->setDescription($domain->description);
        $entity->setUserId($domain->userId->__toString());
        $entity->setIsActive($domain->isActive);
        return $entity;
    }

    public static function updatePersistence(CategoryEntity $entity, Category $domain): void
    {
        $entity->setName($domain->name);
        $entity->setDescription($domain->description);
        $entity->setIsActive($domain->isActive);
    }
}
