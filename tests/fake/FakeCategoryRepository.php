<?php

namespace Tests\fake;

use Src\Domain\Entities\Category;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\ValueObject\UUID;
class FakeCategoryRepository implements CategoryRepository
{
    /**
     * @var Category[]
     */
    private array $categories = [];

    public function save($category): void
    {
        $this->categories[] = $category;
    }


    public function findById(string $id): ?Category
    {
        return array_find($this->categories, fn($c) => $c->id->equals(UUID::fromString($id)));
    }

    public function findAllByUserId(string $userId, ?bool $isActive = true): array
    {
        return array_filter(
            $this->categories,
            fn($c) =>
            $c->userId->equals(UUID::fromString($userId)) &&
            $c->isActive === $isActive
        );
    }
}