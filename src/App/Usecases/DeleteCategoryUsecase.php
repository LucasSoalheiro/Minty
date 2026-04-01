<?php

namespace Src\App\Usecases;

use Src\App\Error\CategoryAlreadyInactive;
use Src\App\Error\CategoryNotFound;
use Src\Domain\Category\CategoryRepository;

class DeleteCategoryUsecase
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    )
    {
    }

    public function execute(string $categoryId): void
    {
        $category = $this->categoryRepository->findById($categoryId);

        if (!$category) {
            throw new CategoryNotFound($categoryId);
        }       

        if (!$category->getIsActive()) {
            throw new CategoryAlreadyInactive();
        }

        $category->deactivate();

        $this->categoryRepository->save($category);
    }
}