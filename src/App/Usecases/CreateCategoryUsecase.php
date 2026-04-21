<?php

namespace Src\App\Usecases;

use Src\App\DTO\CreateCategoryDto;
use Src\App\Error\UserNotFound;
use Src\Domain\Entities\Category;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\Repository\UserRepository;

class CreateCategoryUsecase
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function execute(CreateCategoryDto $dto): void
    {
        $user = $this->userRepository->findById($dto->userId);
        if (!$user) {
            throw new UserNotFound($dto->userId);
        }
        $category = Category::create($dto->name, $dto->description, $user->id);
        $this->categoryRepository->save($category);
    }
}