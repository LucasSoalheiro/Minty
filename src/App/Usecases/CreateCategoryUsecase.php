<?php

namespace Src\App\Usecases;

use Src\App\DTO\CreateCategoryDto;
use Src\App\Error\UserNotFound;
use Src\Domain\Category\Category;
use Src\Domain\Category\CategoryRepository;
use Src\Domain\User\UserRepository;

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
        $category = Category::create($dto->name, $dto->description, $user->getId());
        $this->categoryRepository->save($category);
    }
}