<?php

namespace Tests\App;

use PHPUnit\Framework\TestCase;
use Src\App\DTO\CreateCategoryDto;
use Src\App\Usecases\CreateCategoryUsecase;
use Src\App\Usecases\ListCategoriesUsecase;
use Src\Domain\Entities\User;
use Src\Domain\Repository\CategoryRepository;
use Src\Domain\Repository\UserRepository;
use Src\Domain\ValueObject\Email;
use Src\Domain\ValueObject\Password;
use Tests\fake\FakeCategoryRepository;
use Tests\fake\FakeUserRepository;

class ListCategoriesTest extends TestCase
{
    private CategoryRepository $categoryRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->categoryRepository = new FakeCategoryRepository();
        $this->userRepository = new FakeUserRepository();
    }

    private function makeUser(): User
    {
        $this->userRepository->save(User::create('John Doe', Email::create('john.doe@example.com'), Password::create('P@ssw0rd')));
        return $this->userRepository->findByEmail('john.doe@example.com');
    }
    
    public function createMultipleCategoriesForUser(User $user): void
    {
        $dto1 = new CreateCategoryDto("Test Name 1", "Test Description 1", $user->id->__toString());
        $dto2 = new CreateCategoryDto("Test Name 2", "Test Description 2", $user->id->__toString());
        $createCategoryUsecase = new CreateCategoryUsecase($this->categoryRepository, $this->userRepository);
        $createCategoryUsecase->execute($dto1);
        $createCategoryUsecase->execute($dto2);
    }

    public function testListCategories(): void
    {
        $user = $this->makeUser();
        $this->createMultipleCategoriesForUser($user);
        $listCategoriesUsecase = new ListCategoriesUsecase($this->categoryRepository);
        $categories = $listCategoriesUsecase->execute($user->id->__toString(), null);
        $this->assertCount(2, $categories);
    }

    public function testListActiveCategories(): void
    {
        $user = $this->makeUser();
        $this->createMultipleCategoriesForUser($user);
        $listCategoriesUsecase = new ListCategoriesUsecase($this->categoryRepository);
        $categories = $listCategoriesUsecase->execute($user->id->__toString(), true);
        $this->assertCount(2, $categories);
    }

    public function testListInactiveCategories(): void
    {
        $user = $this->makeUser();
        $this->createMultipleCategoriesForUser($user);
        $listCategoriesUsecase = new ListCategoriesUsecase($this->categoryRepository);
        $categories = $listCategoriesUsecase->execute($user->id->__toString(), false);
        $this->assertCount(0, $categories);
    }
}