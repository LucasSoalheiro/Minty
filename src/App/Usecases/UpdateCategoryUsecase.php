<?php

namespace Src\App\Usecases;

use Src\App\DTO\UpdateCategoryDto;
use Src\App\Error\CategoryNotFound;
use Src\App\Error\NeedToUpdateAtLeastOneField;
use Src\Domain\Repository\CategoryRepository;

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

        if ($dto->name) {
            $category->updateName($dto->name);
        }
        if ($dto->description) {
            $category->updateDescription($dto->description);
        }

        if (!$dto->name && !$dto->description) {
            throw new NeedToUpdateAtLeastOneField();
        }

        $this->categoryRepository->save($category);
    }
}