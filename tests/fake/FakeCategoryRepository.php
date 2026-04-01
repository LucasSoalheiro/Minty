<?php

namespace Tests\fake;

use Src\Domain\Category\Category;
use \Src\Domain\Category\CategoryRepository;
use Src\Domain\ValueObject\UUID;
class FakeCategoryRepository implements CategoryRepository
{
    private array $categories = [];

    public function save($category): void
    {
        $this->categories[] = $category;
    }

    public function findById(string $id): ?Category
    {
        foreach ($this->categories as $category) {
            if ($category->getId()->equals(UUID::fromString($id))) {
                return $category;
            }
        }
        return null;
    }

    public function findAllByUserId(string $userId): array
    {
        $result = [];
        foreach ($this->categories as $category) {
            if ($category->getUserId()->equals(UUID::fromString($userId))) {
                $result[] = $category;
            }
        }
        return $result;
    }
    public function findAllByUserIdAndIsActive(string $userId, bool $isActive): array
    {
        $result = [];
        foreach ($this->categories as $category) {
            if (
                $category->getUserId()->equals(UUID::fromString($userId)) &&
                $category->getIsActive() === $isActive
            ) {
                $result[] = $category;
            }
        }
        return $result;
    }
}