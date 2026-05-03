<?php

namespace Src\App\Usecases;

use Src\App\DTO\ListCategoriesResponse;
use Src\Domain\Repository\CategoryRepository;

class ListCategoriesUsecase
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    /** @return ListCategoriesResponse[] */
    public function execute(string $userId, ?bool $isActive): array
    {
        $categories = $this->categoryRepository->findAllByUserId($userId);
        $filteredCategories = $isActive !== null
            ? array_filter($categories, fn($category) => $category->isActive === $isActive)
            : $categories;

        return array_map(fn($category) => new ListCategoriesResponse(
            id: $category->id,
            name: $category->name,
            description: $category->description,
            isActive: $category->isActive,
        ), $filteredCategories);
    }
}