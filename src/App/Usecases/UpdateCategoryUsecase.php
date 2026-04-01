<?php

namespace Src\App\Usecases;

use Src\App\DTO\UpdateCategoryDto;
use Src\App\Error\CategoryNotFound;
use Src\Domain\Category\CategoryRepository;

class UpdateCategoryUsecase
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function execute(UpdateCategoryDto $dto): void
    {
        $category = $this->categoryRepository->findById($dto->id);

        if (!$category) {
            throw new CategoryNotFound($dto->id);   
        }

        if ($dto->name !== null) {
            $category->updateName($dto->name);
        }
        if ($dto->description !== null) {
            $category->updateDescription($dto->description);
        }

        $this->categoryRepository->save($category);
    }
}